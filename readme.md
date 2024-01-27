///////////////////////////////////////////////////////////////
TO USE THIS AS A BOILER PLATE, a few changes should be made:
///////////////////////////////////////////////////////////////
-modify DBNAME on .env (other const on .env are: DBHOST=localhost,DBUSER,DBPASS,DBCHARSET=utf8mb4)

-modify namespaces and composer.json: (original was YoucabOk)

-modify "domain" filed on config/session

-modify "BASE_URL" as needed on forgottenpass.php . Also modify all mail attributes

//////////// Please notice that:

-html headers are included on headar.inc and footer.php

-every page is supposed to be wrapped into a main tag

/////////// IMPROVEMENTS
-LAST THING to improve: on Reset_pass there is a loading_container that is not showing properly yet, maybe it can be done using AJAX, but haven´t tried yet.

