import docx
import sys

def inspect_docx_images(filepath):
    doc = docx.Document(filepath)
    current_heading = "General"
    sections = {current_heading: {'images': 0, 'paragraphs': []}}
    
    for i, p in enumerate(doc.paragraphs):
        text = p.text.strip()
        has_image = "graphic" in p._element.xml
        
        # If it's a heading
        if text.startswith("REALIZAR L"):
            current_heading = text
            sections[current_heading] = {'images': 0, 'paragraphs': []}
            
        if has_image:
            sections[current_heading]['images'] += p._element.xml.count("graphic")
            
    for heading, data in sections.items():
        print(f"[{data['images']} imgs] {heading[:100]}")

if __name__ == '__main__':
    inspect_docx_images(sys.argv[1])
