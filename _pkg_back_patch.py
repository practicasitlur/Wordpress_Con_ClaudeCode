import re

path = r'c:\Users\usuario\Documents\lur\wordpress\tema-local\page-templates\catering.php'

with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Remove pkg-back-hint CSS block
content = re.sub(
    r'\t\t\.pkg-back \.pkg-back-hint \{[^}]+\}\n',
    '',
    content
)

# 2. Remove all pkg-back-hint HTML spans
content = re.sub(
    r'\t\t\t\t\t\t\t<span class="pkg-back-hint">↩ Haz clic para volver</span>\n',
    '',
    content
)

# 3. Change back face background from #000 to #F1EFED and update text colors
old_back_css = """\t\t.pkg-back {
\t\t\tbackground: #000;
\t\t\tcolor: #fff;
\t\t\ttransform: rotateY(180deg);
\t\t\tdisplay: flex;
\t\t\tflex-direction: column;
\t\t\tjustify-content: center;
\t\t\tpadding: 2rem 1.75rem;
\t\t}
\t\t.pkg-back .pkg-back-label {
\t\t\tfont-family: 'Space Mono', monospace;
\t\t\tfont-size: 0.6rem;
\t\t\tletter-spacing: 0.15em;
\t\t\ttext-transform: uppercase;
\t\t\tcolor: #FEE800;
\t\t\tdisplay: block;
\t\t\tmargin-bottom: 0.75rem;
\t\t}
\t\t.pkg-back h4 {
\t\t\tfont-size: 1.1rem;
\t\t\tfont-weight: 800;
\t\t\tmargin-bottom: 1rem;
\t\t\tcolor: #fff;
\t\t\tline-height: 1.2;
\t\t}
\t\t.pkg-back p {
\t\t\tfont-size: 0.82rem;
\t\t\tcolor: #b0b0a2;
\t\t\tline-height: 1.6;
\t\t\tmargin-bottom: 1.5rem;
\t\t}"""

new_back_css = """\t\t.pkg-back {
\t\t\tbackground: #F1EFED;
\t\t\tcolor: #000;
\t\t\ttransform: rotateY(180deg);
\t\t\tdisplay: flex;
\t\t\tflex-direction: column;
\t\t\tjustify-content: center;
\t\t\tpadding: 2rem 1.75rem;
\t\t\tborder: 3px solid #000;
\t\t}
\t\t.pkg-back .pkg-back-label {
\t\t\tfont-family: 'Space Mono', monospace;
\t\t\tfont-size: 0.6rem;
\t\t\tletter-spacing: 0.15em;
\t\t\ttext-transform: uppercase;
\t\t\tcolor: #5F6776;
\t\t\tdisplay: block;
\t\t\tmargin-bottom: 0.75rem;
\t\t}
\t\t.pkg-back h4 {
\t\t\tfont-size: 1.1rem;
\t\t\tfont-weight: 800;
\t\t\tmargin-bottom: 1rem;
\t\t\tcolor: #000;
\t\t\tline-height: 1.2;
\t\t}
\t\t.pkg-back p {
\t\t\tfont-size: 0.82rem;
\t\t\tcolor: #4a4a4a;
\t\t\tline-height: 1.6;
\t\t\tmargin-bottom: 0;
\t\t}"""

content = content.replace(old_back_css, new_back_css)

# 4. Add flip indicator arrow to .pkg-img-wrapper using ::after
old_img_wrapper_css = """\t\t.pkg-front .pkg-img-wrapper img.no-zoom {
\t\t\ttransform: scale(1);
\t\t}"""

new_img_wrapper_css = """\t\t.pkg-front .pkg-img-wrapper img.no-zoom {
\t\t\ttransform: scale(1);
\t\t}
\t\t.pkg-flip:not(.flipped) .pkg-img-wrapper::after {
\t\t\tcontent: '↻';
\t\t\tposition: absolute;
\t\t\tbottom: 8px;
\t\t\tright: 10px;
\t\t\tfont-size: 1.1rem;
\t\t\tcolor: #000;
\t\t\topacity: 0.3;
\t\t\ttransition: opacity 0.2s;
\t\t\tpointer-events: none;
\t\t}
\t\t.pkg-flip:not(.flipped):hover .pkg-img-wrapper::after {
\t\t\topacity: 0.7;
\t\t}"""

content = content.replace(old_img_wrapper_css, new_img_wrapper_css)

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Done")
