# Ethel & Myrtle

Ethel & Myrtle is a fashion jewelry and accessories e-commerce site.

## Project

Two of the customizations on this website were the addition of a **Find a Sales Rep** page and a **Store Locator** page.

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

## Find a Sales Rep

This feature allows the client to maintain a list of sales representatives and the states that they serve. The front end of the website renders a map of the US on which customers can click on a state to see a list of sales representatives covering that territory.

http://ethelandmyrtle.com/index.php?route=information/salesrep

## Store Locator

On the backend, the client can import a spreadsheet representing stores that sell their merchandise. The information is parsed, addresses geo-coded and stored in the database. The front-end allows store customers to lookup, via a ZIP code, the stores in their area.

http://ethelandmyrtle.com/index.php?route=information/locator
