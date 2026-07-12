import http from "node:http";
import { readFile } from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { JSDOM } from "jsdom";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const HOST = "0.0.0.0";
const PORT = 3000;
const MAX_BODY_BYTES = 16 * 1024;

const DEFAULT_PROFILE = Object.freeze({
  name: "guest",
  bio: "기본 공개 프로필입니다."
});

const PRIVATE_PROFILE = Object.freeze({
  email: "guest@example.local",
  reviewNote: "internal-only: 신규 계정 검토 필요"
});

const DEFAULT_PROFILE_TEXT = JSON.stringify(DEFAULT_PROFILE);
let storedProfileText = DEFAULT_PROFILE_TEXT;

const [jquerySource, expectedJqueryVersion, indexHtml, appJavaScript] =
  await Promise.all([
    readFile(path.join(__dirname, "runtime", "jquery.js"), "utf8"),
    readFile(path.join(__dirname, "runtime", "jquery.version"), "utf8"),
    readFile(path.join(__dirname, "public", "index.html"), "utf8"),
    readFile(path.join(__dirname, "public", "app.js"), "utf8")
  ]);

const dom = new JSDOM("<!doctype html><html><body></body></html>", {
  runScripts: "outside-only",
  url: "http://profile-service.local/"
});

dom.window.eval(jquerySource);
const $ = dom.window.jQuery;
const jqueryVersion = expectedJqueryVersion.trim();

if (!$ || typeof $.extend !== "function") {
  throw new Error("jQuery 초기화에 실패했습니다.");
}

if ($.fn.jquery !== jqueryVersion) {
  throw new Error(
    `빌드 버전(${jqueryVersion})과 실행 버전(${$.fn.jquery})이 다릅니다.`
  );
}

const BASE_HEADERS = {
  "Cache-Control": "no-store",
  "X-Content-Type-Options": "nosniff"
};

function sendText(res, statusCode, contentType, body, extraHeaders = {}) {
  res.writeHead(statusCode, {
    ...BASE_HEADERS,
    ...extraHeaders,
    "Content-Type": contentType,
    "Content-Length": Buffer.byteLength(body)
  });
  res.end(body);
}

function sendJson(res, statusCode, value) {
  sendText(
    res,
    statusCode,
    "application/json; charset=utf-8",
    `${JSON.stringify(value, null, 2)}\n`
  );
}

async function readRequestBody(req) {
  const chunks = [];
  let total = 0;

  for await (const chunk of req) {
    total += chunk.length;
    if (total > MAX_BODY_BYTES) {
      const error = new Error("요청 본문은 16 KiB 이하여야 합니다.");
      error.statusCode = 413;
      throw error;
    }
    chunks.push(chunk);
  }

  return Buffer.concat(chunks).toString("utf8");
}

function parseJsonObject(rawText) {
  let parsed;

  try {
    parsed = JSON.parse(rawText);
  } catch {
    const error = new Error("유효한 JSON 객체가 아닙니다.");
    error.statusCode = 400;
    throw error;
  }

  if (parsed === null || Array.isArray(parsed) || typeof parsed !== "object") {
    const error = new Error("JSON 최상위 값은 객체여야 합니다.");
    error.statusCode = 400;
    throw error;
  }

  return parsed;
}

function hasOwn(object, key) {
  return Object.prototype.hasOwnProperty.call(object, key);
}

function clearLabPollution() {
  delete Object.prototype.includePrivate;
  delete Object.prototype.polluted;
}

function createPublicProfile(profile, exportPolicy) {
  const output = {
    name: typeof profile.name === "string" ? profile.name : "",
    bio: typeof profile.bio === "string" ? profile.bio : ""
  };

  if (exportPolicy.includePrivate === true) {
    output.private = { ...PRIVATE_PROFILE };
  }

  return output;
}

const server = http.createServer(async (req, res) => {
  try {
    const url = new URL(req.url ?? "/", `http://${req.headers.host ?? "localhost"}`);

    if (req.method === "GET" && url.pathname === "/") {
      sendText(
        res,
        200,
        "text/html; charset=utf-8",
        indexHtml,
        {
          "Content-Security-Policy":
            "default-src 'none'; script-src 'self'; connect-src 'self'; base-uri 'none'; form-action 'self'"
        }
      );
      return;
    }

    if (req.method === "GET" && url.pathname === "/app.js") {
      sendText(
        res,
        200,
        "text/javascript; charset=utf-8",
        appJavaScript
      );
      return;
    }

    if (req.method === "GET" && url.pathname === "/healthz") {
      sendJson(res, 200, { status: "ok", jqueryVersion: $.fn.jquery });
      return;
    }

    if (req.method === "POST" && url.pathname === "/api/reset") {
      clearLabPollution();
      storedProfileText = DEFAULT_PROFILE_TEXT;
      sendJson(res, 200, {
        reset: true,
        jqueryVersion: $.fn.jquery
      });
      return;
    }

    if (req.method === "POST" && url.pathname === "/api/profile") {
      const contentType = String(req.headers["content-type"] ?? "").toLowerCase();
      if (!contentType.startsWith("application/json")) {
        sendJson(res, 415, {
          error: "Content-Type은 application/json이어야 합니다."
        });
        return;
      }

      const rawBody = await readRequestBody(req);
      const parsed = parseJsonObject(rawBody);

      // 문자열 원문을 보관해 JSON.parse 시 own '__proto__' 속성을 유지한다.
      storedProfileText = rawBody.trim();

      sendJson(res, 200, {
        saved: true,
        sourceHasOwnProto: hasOwn(parsed, "__proto__")
      });
      return;
    }

    if (req.method === "GET" && url.pathname === "/api/profile/export") {
      if (Object.prototype.includePrivate !== undefined) {
        sendJson(res, 409, {
          error: "이미 오염된 상태입니다. 초기화 후 다시 실행하세요."
        });
        return;
      }

      const sourceProfile = parseJsonObject(storedProfileText);
      const sourceHasOwnProto = hasOwn(sourceProfile, "__proto__");

      const before = {
        objectPrototypeIncludePrivate: null
      };

      // CVE-2019-11358의 실제 취약 지점이다.
      const mergedProfile = $.extend(true, {}, DEFAULT_PROFILE, sourceProfile);

      // 프로필 입력과 별도로 생성되는 내보내기 정책 객체다.
      const exportPolicy = {};
      const exportedProfile = createPublicProfile(mergedProfile, exportPolicy);

      const after = {
        objectPrototypeIncludePrivate:
          Object.prototype.includePrivate === undefined
            ? null
            : Object.prototype.includePrivate,
        objectPrototypePolluted:
          Object.prototype.polluted === undefined
            ? null
            : Object.prototype.polluted,
        exportPolicyHasOwnIncludePrivate: hasOwn(
          exportPolicy,
          "includePrivate"
        ),
        exportPolicyInheritedIncludePrivate:
          exportPolicy.includePrivate === undefined
            ? null
            : exportPolicy.includePrivate
      };

      const privateDataExposed = hasOwn(exportedProfile, "private");
      const vulnerable =
        sourceHasOwnProto === true &&
        after.objectPrototypeIncludePrivate === true &&
        after.exportPolicyHasOwnIncludePrivate === false &&
        after.exportPolicyInheritedIncludePrivate === true &&
        privateDataExposed === true;

      sendJson(res, 200, {
        result: vulnerable ? "VULNERABLE" : "NOT_VULNERABLE",
        jqueryVersion: $.fn.jquery,
        privateDataExposed,
        exportedProfile,
        evidence: {
          sourceHasOwnProto,
          before,
          after
        }
      });
      return;
    }

    sendJson(res, 404, { error: "Not Found" });
  } catch (error) {
    const statusCode = Number(error?.statusCode) || 500;
    sendJson(res, statusCode, {
      error: statusCode === 500 ? "Internal Server Error" : String(error.message)
    });

    if (statusCode === 500) {
      console.error(error);
    }
  }
});

server.listen(PORT, HOST, () => {
  console.log(`Open in browser: http://127.0.0.1:${PORT}`);
  console.log(`jQuery version: ${$.fn.jquery}`);
});
