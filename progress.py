import json
import sys
from pathlib import Path


def end_with_error(error_message):
    print(json.dumps({
        "status": "aborted",
        "message": error_message
    }))
    exit(0)


def end_with_progress(current, max):
    if current == max:
        status = {
            "status": "completed",
        }
    else:
        status = {
            "status": "in progress"
        }
    progress = {
        "current": current,
        "max": max
    }
    print(json.dumps({**status, **progress}))
    exit(0)


def count_extensions(directory, extension):
    count = 0
    for file in directory.glob(f"*.{extension}"):
        count += 1
    return count


if __name__ == "__main__":
    hash_name = sys.argv[1]
    dir = Path(f"./results/{hash_name}")
    if not dir.is_dir():
        end_with_error("Nieprawidłowa nazwa kodowa. Spróbuj jeszcze raz lub zgłoś ten błąd jeżeli będzie się powtarzał.")
    current_pdf_count = count_extensions(directory=dir, extension="pdf")
    current_svg_count = count_extensions(directory=dir, extension="svg")
    end_with_progress(current=current_pdf_count, max=current_svg_count+1)
