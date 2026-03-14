import zipfile
import xml.etree.ElementTree as ET
import sys
import os

def extract_docx(docx_path):
    print(f"Extracting docx: {docx_path}")
    try:
        with zipfile.ZipFile(docx_path) as docx:
            xml_content = docx.read('word/document.xml')
            tree = ET.fromstring(xml_content)
            
            # Simple text extraction
            namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
            text_elements = tree.findall('.//w:t', namespaces)
            text = ''.join([element.text for element in text_elements if element.text])
            print("--- TEXT EXTRACT ---")
            print(text)
            print("--- END TEXT EXTRACT ---")
            
            # List images
            image_list = [f.filename for f in docx.infolist() if f.filename.startswith('word/media/')]
            print(f"--- IMAGES ({len(image_list)}) ---")
            for img in image_list:
                print(img)
            print("--- END IMAGES ---")
                
    except Exception as e:
        print(f"Error: {e}")

extract_docx(sys.argv[1])
