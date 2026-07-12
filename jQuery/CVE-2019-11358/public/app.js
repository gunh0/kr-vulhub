"use strict";

const form = document.querySelector("#profile-form");
const exportButton = document.querySelector("#export-button");
const resetButton = document.querySelector("#reset-button");
const statusElement = document.querySelector("#status");
const resultElement = document.querySelector("#result");
const versionElement = document.querySelector("#jquery-version");

function showResult(status, data) {
  statusElement.textContent = status;
  resultElement.textContent = JSON.stringify(data, null, 2);
}

async function requestJson(url, options = {}) {
  const response = await fetch(url, options);
  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.error || `HTTP ${response.status}`);
  }

  return data;
}

async function loadVersion() {
  try {
    const data = await requestJson("/healthz");
    versionElement.textContent = data.jqueryVersion;
  } catch (error) {
    versionElement.textContent = "확인 실패";
    showResult("서버 확인 실패", { error: error.message });
  }
}

form.addEventListener("submit", async (event) => {
  event.preventDefault();

  const profile = {
    name: document.querySelector("#name").value,
    bio: document.querySelector("#bio").value
  };

  try {
    const data = await requestJson("/api/profile", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(profile)
    });
    showResult("프로필을 저장했습니다.", data);
  } catch (error) {
    showResult("저장 실패", { error: error.message });
  }
});

exportButton.addEventListener("click", async () => {
  try {
    const data = await requestJson("/api/profile/export");
    showResult(
      data.privateDataExposed
        ? "비공개 정보가 공개 응답에 포함되었습니다."
        : "공개 프로필만 반환되었습니다.",
      data
    );
  } catch (error) {
    showResult("공개 프로필 확인 실패", { error: error.message });
  }
});

resetButton.addEventListener("click", async () => {
  try {
    const data = await requestJson("/api/reset", { method: "POST" });
    document.querySelector("#name").value = "guest";
    document.querySelector("#bio").value = "기본 공개 프로필입니다.";
    showResult("초기화했습니다.", data);
  } catch (error) {
    showResult("초기화 실패", { error: error.message });
  }
});

loadVersion();
