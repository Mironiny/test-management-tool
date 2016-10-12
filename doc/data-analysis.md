# Datová analýza 

## Co zobrazovat uživateli?

* **Správu jednotlivých SUT**
	* Zobrazení statistik (kolik je dokončeno, odhadovaná doba do konce)

* **Správu Test Requirement**
	* Přidávání nových TR
	* Editace

* **Test Run**
	* Přirazení zodpovednosti za provedeni 1 .. N testerům
	* Zobrazit výsledky jednotlivých běhů
	* Zobrazení statistik (kolik je dokončeno, odhadovaná doba do konce)

* **Správu Test Suite**
	* Přidávání a editace

* **Správu Test Case**
	* Přidávání a editace


## Co je třeba mít uložené v DB?

* **System Under Test**

	Nejvyšší vrstva - definuje projekt či testovaný systém
	* Jméno projektu
	* Manažerské informace jako odhadovaná doba testování, skutečná doba testo-
	  vání atd.

* **Test Requirement**

	Specifikace jednotlivých požadavků na SUT, pravděpodobně v 
	textové podobě

* **Test Run**

	Definuje jedno spuštění daného Test Case/Suite
	* Výsledky běhu - Pass vs. Fail
	* Čás spuštění, doba provádění běhu
	* Člověk, zodpovědný za spuštění

* **Test Executor**

	Definuje, kdo provádí dané Test Case/Suite
	* Člověk
	* Nástroj pro spouštění automatizovaných testů - např. Jenkins nebo
	  Selenium

* **Test Suite**

	Nadmnožina Test Case
	* Cíle
	* Verze
	* Dokumentace

* **Test Case**

	Definuje jeden testovací příklad
	* Test fixture
	* Popis 
	* V případě manuální testu návod na provedení testu
	* V případě automatizovaého testu - zdrojový kod
	* Datum vytvoření
	* Autor


