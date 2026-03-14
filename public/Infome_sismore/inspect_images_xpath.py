import docx
import sys
from docx.document import Document

def inspect_docx_images(filepath):
    doc = docx.Document(filepath)
    current_heading = "General"
    sections = {current_heading: {'images': 0, 'paragraphs': []}}
    
    for i, p in enumerate(doc.paragraphs):
        text = p.text.strip()
        
        # Check if heading
        if text.startswith("REALIZAR L"):
            current_heading = text
            sections[current_heading] = {'images': 0, 'paragraphs': []}
            
        # Check for namespaces
        images_in_p = len(p._element.xpath('.//pic:pic'))
        if images_in_p > 0:
            sections[current_heading]['images'] += images_in_p
            
    for heading, data in sections.items():
        print(f"[{data['images']} imgs] {heading[:100]}")

if __name__ == '__main__':
    inspect_docx_images(sys.argv[1])
