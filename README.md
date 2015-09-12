# Ethel & Myrtle

Ethel & Myrtle is a fashion jewelry and accessories e-commerce site.

## Project

Two of the customizations on this website were the addition of a **Find a sales Rep** page and a **store locator** page.

## Structure

The Opencart framework uses a MVC design pattern. Working within this framework I added files detailed below to the existing structure.

```
contentcustoms/
+-- admin/
¦	+-- contorller/
¦		+-- sale/rep.php
¦		+-- tool/import_stores.php
¦	+-- language/
¦	+-- model/
¦		+-- sale/rep.php
¦		+-- tool/import_stores.php
¦	+-- view/
¦		+-- template
¦			+-- sale
¦				+-- rep_form.tpl
¦				+-- rep_list.tpl
¦			+-- tool
¦				+-- import_stores.tpl
+-- catalog/
¦	+-- contorller/
¦		+-- information/
¦			+-- reps.php
¦			+-- stors.php
¦	+-- language/
¦	+-- model/
¦		+-- information/
¦			+-- reps.php
¦			+-- stores.php
¦	+-- view/
¦		+-- theme
¦			+-- theme035
¦				+-- template
¦					+-- information
¦						+-- reps.tpl
¦						+-- stores.tpl
```
