import os
import re

VIEWS_DIR = '/var/www/html/indonesiatourguide/resources/views'

def fix_content(content):
    # 1. Fix: <i data-lucide="map-pin" ></i class="mr-1"></i> -> <i data-lucide="map-pin" class="mr-1"></i>
    pattern_class = re.compile(r'<i data-lucide="([^"]+)"\s*></i\s+class="([^"]+)"></i>')
    content = pattern_class.sub(r'<i data-lucide="\1" class="\2"></i>', content)
    
    # 2. Fix: <i data-lucide="search" ></i></i> -> <i data-lucide="search"></i>
    pattern_empty = re.compile(r'<i data-lucide="([^"]+)"\s*></i></i>')
    content = pattern_empty.sub(r'<i data-lucide="\1"></i>', content)

    # 3. Fix: any escaped single quotes in PHP tags
    # Find <?php ... ?> blocks and unescape backslashed single quotes
    def unescape_php(match):
        php_block = match.group(0)
        # Unescape \\' or \'
        fixed = php_block.replace("\\'", "'").replace("\'", "'")
        return fixed
        
    php_pattern = re.compile(r'<\?php.*?\?>', re.DOTALL)
    content = php_pattern.sub(unescape_php, content)
    
    return content

if __name__ == '__main__':
    fixed_count = 0
    for root_dir, dirs, files in os.walk(VIEWS_DIR):
        for file in files:
            if file.endswith('.php'):
                file_path = os.path.join(root_dir, file)
                with open(file_path, 'r', encoding='utf-8') as f:
                    orig = f.read()
                
                fixed = fix_content(orig)
                if fixed != orig:
                    with open(file_path, 'w', encoding='utf-8') as f:
                        f.write(fixed)
                    print(f"Fixed: {file_path}")
                    fixed_count += 1
                    
    print(f"\nDone. Cleaned up {fixed_count} files.")
