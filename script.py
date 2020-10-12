import shutil
from pathlib import Path
from urllib.parse import urlparse
from urllib.request import urlopen
from urllib.error import HTTPError
from datetime import datetime
import sys
import itertools
import os
import hashlib

from PyPDF2 import PdfFileWriter, PdfFileReader, PdfFileMerger
from reportlab.graphics import renderPM, renderPDF
from reportlab.pdfgen import canvas
from reportlab.platypus import SimpleDocTemplate
from svglib.svglib import svg2rlg


def is_url_bbb(url):
    parsed_url = urlparse(url)
    hostname = '{uri.netloc}'.format(uri=parsed_url)
    return hostname.endswith(".put.poznan.pl") and hostname.startswith("bbb")


def save_files_to_dir(dir_name, url):
    for i in itertools.count(start=1, step=1):
        url_with_number = url + "/" + i.__str__()
        try:
            with(urlopen(url=url_with_number)) as content:
                svg_content = content.read().decode('utf-8')
                with(open('{}/{:03d}.svg'.format(dir_name, i), "w")) as svg_file:
                    svg_file.write(svg_content)
        except HTTPError as e:
            if e.code == 404:
                break
            else:
                print("Pobieranie prezentacji nie powiodło się. Spróbuj ponownie bądź upewnij się, że podany link jest prawidłowy")
                exit(3)


def create_dir_with_name(dir_name):
    try:
        os.makedirs(name=dir_name)
    except OSError as e:
        if not os.path.isdir(dir_name):
            print("Pobieranie prezentacji nie powiodło się. Spróbuj ponownie!")
            exit(2)


def make_pdf_from_dir(dir_name, pdf_name):
    result_dir_path = Path(dir_name)
    merger = PdfFileMerger()
    pdfs = []
    for file in result_dir_path.iterdir():
        if file.suffix == ".svg":
            image = svg2rlg(str(file))
            subpdf_name = str(file).replace(".svg", ".pdf")
            renderPDF.drawToFile(image, subpdf_name)
            pdfs.append(subpdf_name)
    pdfs.sort()
    for pdf in pdfs:
        merger.append(pdf)
    merger.write(pdf_name)
    merger.close()


def remove_content_older_than(minutes, dir_name):
    dir = Path(dir_name)
    now = int(datetime.now().timestamp())
    for child in dir.iterdir():
        if now - child.stat().st_ctime > int(minutes * 60):
            if child.is_dir():
                shutil.rmtree(str(child))
            else:
                child.unlink()


if __name__ == "__main__":
    url = sys.argv[1]
    if not is_url_bbb(url):
        print("Podany link jest nieprawidłowy")
        exit(1)
    url_without_number = "/".join(url.split("/")[:-1])
    results_dir_name = "results"
    remove_content_older_than(minutes=1, dir_name=results_dir_name)
    dir = results_dir_name + "/" + hashlib.md5(datetime.now().strftime("%H:%M:%S").encode('utf-8')).hexdigest()
    create_dir_with_name(dir_name=dir)
    save_files_to_dir(dir_name=dir, url=url_without_number)
    pdf_file_name = '{}/{}.pdf'.format(dir, "wyklady")
    make_pdf_from_dir(dir_name=dir, pdf_name=pdf_file_name)
    print(pdf_file_name)
