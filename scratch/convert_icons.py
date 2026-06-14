import os
import re

# Directory to scan
VIEWS_DIR = '/var/www/html/indonesiatourguide/resources/views'

# Mapping of specific FontAwesome icon names to Lucide equivalents
icon_mapping = {
    'magnifying-glass': 'search',
    'location-crosshairs': 'locate-fixed',
    'map-location-dot': 'map-pinned',
    'location-dot': 'map-pin',
    'circle-check': 'circle-check',
    'circle-exclamation': 'circle-alert',
    'spinner': 'loader-2',
    'align-left': 'align-left',
    'comments': 'message-square',
    'message': 'message-square',
    'cart-shopping': 'shopping-cart',
    'shield-halved': 'shield',
    'headset': 'headphones',
    'right-to-bracket': 'log-in',
    'right-from-bracket': 'log-out',
    'user-pen': 'user-round-pen',
    'city': 'building-2',
    'suitcase': 'briefcase',
    'bars': 'menu',
    'xmark': 'x',
    'house': 'home',
    'chevron-down': 'chevron-down',
    'chevron-left': 'chevron-left',
    'chevron-right': 'chevron-right',
    'plus': 'plus',
    'arrow-right': 'arrow-right',
    'map-pin': 'map-pin',
    'clock': 'clock',
    'star': 'star',
    'instagram': 'instagram',
    'facebook-f': 'facebook',
    'youtube': 'youtube',
    'heart': 'heart',
    'ban': 'ban',
    'user': 'user',
    'envelope': 'mail',
    'phone': 'phone',
    'lock': 'lock',
    'user-plus': 'user-plus',
    'chart-line': 'trending-up',
    'trash': 'trash-2',
    'edit': 'pencil',
    'pencil': 'pencil',
    'eye': 'eye',
    'eye-slash': 'eye-off',
    'calendar': 'calendar',
    'info-circle': 'info',
    'route': 'route',
    'check': 'check',
    'times': 'x',
    'lock-open': 'lock-open',
    'key': 'key',
    'cash-register': 'wallet',
    'credit-card': 'credit-card',
    'paper-plane': 'send',
    'star-half-stroke': 'star-half',
}

def convert_tag(match):
    tag_content = match.group(0)
    
    # 1. Parse out the class attribute
    class_match = re.search(r'class=["\']([^"\']*)["\']', tag_content)
    if not class_match:
        return tag_content
        
    classes_str = class_match.group(1)
    classes = classes_str.split()
    
    # Verify if it's a FontAwesome icon
    is_fa = False
    fa_icon = None
    remaining_classes = []
    
    for c in classes:
        # Check for fontawesome prefixes
        if c in ['fa-solid', 'fa-regular', 'fa-brands', 'fa-duotone', 'fa-light', 'fa', 'fab', 'fas', 'far']:
            is_fa = True
        elif c.startswith('fa-'):
            fa_icon = c[3:]
        else:
            remaining_classes.append(c)
            
    if not is_fa or not fa_icon:
        return tag_content
        
    # Map to Lucide equivalent
    lucide_icon = icon_mapping.get(fa_icon, fa_icon)
    
    # Rebuild tag without original class attribute first
    other_attrs = re.sub(r'class=["\'][^"\']*["\']', '', tag_content)
    # Strip <i and >/ />
    other_attrs = re.sub(r'^<\s*i\s*', '', other_attrs)
    other_attrs = re.sub(r'\s*/?>\s*$', '', other_attrs)
    other_attrs = other_attrs.strip()
    
    # Form the new Lucide markup
    new_tag = f'<i data-lucide="{lucide_icon}"'
    if other_attrs:
        new_tag += f' {other_attrs}'
    if remaining_classes:
        new_tag += f' class="{" ".join(remaining_classes)}"'
    new_tag += '></i>'
    
    return new_tag

def process_file(file_path):
    print(f"Checking: {file_path}")
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Pattern to match <i> elements with fontawesome classes
    pattern = re.compile(r'<i\b[^>]*>(?:</i>)?')
    new_content = pattern.sub(convert_tag, content)
    
    # Specific edge case: dynamic rating star in package_detail.php
    # class="fa-<?php echo $i < $rev['rating'] ? 'solid' : 'regular'; ?> fa-star"
    # Convert to: data-lucide="star" class="<?php echo $i < $rev['rating'] ? 'text-amber-400 fill-amber-400' : 'text-slate-600'; ?>"
    star_pattern = r'<i class="fa-<\?php echo \$i < \$rev\[\'rating\'\] \? \'solid\' : \'regular\'; \?> fa-star"></i>'
    star_replacement = r'<i data-lucide="star" class="<?php echo $i < $rev[\'rating\'] ? \'text-amber-400 fill-amber-400\' : \'text-slate-600\'; ?>"></i>'
    new_content = re.sub(star_pattern, star_replacement, new_content)

    # Similar dynamic rating star with rating count if any:
    # E.g. class="fa-<?php echo $i < $guide['rating'] ... fa-star"
    guide_star_pattern = r'<i class="fa-<\?php echo \$i < ([^?]+) \? \'solid\' : \'regular\'; \?> fa-star"></i>'
    guide_star_replacement = r'<i data-lucide="star" class="<?php echo $i < \1 ? \'text-amber-400 fill-amber-400\' : \'text-slate-600\'; ?>"></i>'
    new_content = re.sub(guide_star_pattern, guide_star_replacement, new_content)

    # Dynamic spinner setting in JS:
    # btn.innerHTML = `<i class="fa-solid fa-spinner animate-spin text-teal-400"></i> <span>Mencari...</span>`;
    # Convert to: `<i data-lucide="loader-2" class="animate-spin text-teal-400"></i> <span>Mencari...</span>`; lucide.createIcons({ root: btn });
    js_spinner_pattern = r'btn\.innerHTML = `\s*<i class="fa-solid fa-spinner animate-spin text-teal-400"></i>\s*<span>Mencari\.\.\.</span>\s*`;'
    js_spinner_replacement = 'btn.innerHTML = `<i data-lucide="loader-2" class="animate-spin text-teal-400"></i> <span>Mencari...</span>`;\\n        lucide.createIcons({ root: btn });'
    new_content = re.sub(js_spinner_pattern, js_spinner_replacement, new_content)
    
    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"   => CONVERTED")
        return True
    return False

if __name__ == '__main__':
    converted_count = 0
    for root_dir, dirs, files in os.walk(VIEWS_DIR):
        for file in files:
            if file.endswith('.php'):
                file_path = os.path.join(root_dir, file)
                if process_file(file_path):
                    converted_count += 1
                    
    print(f"\nDone. Successfully converted {converted_count} files.")
