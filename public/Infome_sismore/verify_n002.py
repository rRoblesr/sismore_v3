import docx
import sys

def verify():
    filepath = sys.argv[1]
    doc = docx.Document(filepath)
    print(f"Verifying {filepath}...")
    
    for i, p in enumerate(doc.paragraphs):
        text = p.text.strip()
        if text.startswith("REALIZAR L"):
            print(f"\n[SECTION] {text[:50]}...")
        elif text.startswith("Esta actividad"):
            print(f"  [DESC] {text[:50]}...")
        elif text.startswith("Figura "):
            print(f"  [CAPTION] {text}")

if __name__ == '__main__':
    verify()
