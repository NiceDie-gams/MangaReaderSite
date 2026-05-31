import os

def print_tree(start_path, prefix="", ignore_dirs=None, ignore_ext=None):
    if ignore_dirs is None:
        ignore_dirs = []
    if ignore_ext is None:
        ignore_ext = []

    items = sorted(os.listdir(start_path))
    for i, item in enumerate(items):
        path = os.path.join(start_path, item)

        # Пропускаем игнорируемые папки
        if item in ignore_dirs:
            continue

        # Пропускаем файлы по расширению
        if os.path.isfile(path):
            ext = os.path.splitext(item)[1].lower()
            if ext in ignore_ext:
                continue

        connector = "└── " if i == len(items) - 1 else "├── "
        print(prefix + connector + item)

        if os.path.isdir(path):
            new_prefix = prefix + ("    " if i == len(items) - 1 else "│   ")
            print_tree(path, new_prefix, ignore_dirs, ignore_ext)

if __name__ == "__main__":
    project_root = "C:/Users/user/Desktop/институт/6 семестр/web/MangaReaderSite"
    ignore_dirs = ["vendor", "node_modules", ".git", "__pycache__", "manga"]
    ignore_ext = [".png", ".jpg", ".jpeg", ".gif"]

    print(project_root)
    print_tree(project_root, ignore_dirs=ignore_dirs, ignore_ext=ignore_ext)
