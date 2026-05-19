import sys
from pypdf import PdfReader

try:
    reader = PdfReader(r'catatan\BRD_SmartCRM79_Formal_Approval-1-.docx.pdf')
    text = '\n'.join(page.extract_text() for page in reader.pages)
    with open('catatan/brd_text.txt', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Success")
except Exception as e:
    print(f"Error: {e}")
