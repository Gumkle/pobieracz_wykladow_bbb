import hashlib
import subprocess
import sys
from datetime import datetime

if __name__ == "__main__":
    url = sys.argv[1]
    hashname = hashlib.md5(datetime.now().strftime("%H:%M:%S").encode('utf-8')).hexdigest()
    subprocess.Popen(["./venv/bin/python3", "./process.py", url, hashname])
    print(hashname)
