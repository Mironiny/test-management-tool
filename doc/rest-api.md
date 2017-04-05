# Rest API pro Test-management-tool
(Verze 1.0)

## Přehled

Následující dokument poskytne dokumentaci k API k test-management-toolu, které je
postavené principu RESTu, mluvíme tedy o REST API.

V následujících částech bude dokumentován serializační formát, autentizace k API a dále jednotlivé
zdroje (resources), s nimiž jde pomocí API zacházet.

Podsekce:

[Test suite](#test-suites)

[Test case](#test-cases)

[Project](#projects)

## Serializační formát

Aplikace podporuje příjem požadavků ve formátu JSON. Aplikace odpovídá rovněž ve
formátu JSON. Jiné formáty nebudou akceptovány.

## Adresace

Standartní adresace api je *host/api/v**X**/resource*, kde x je číslo verze daného api.

## Autentizace

Autentizace je implenetovaná pomocí tzn. API tokenu, který je předáván při každém
requestu do aplikace. Token je předáván v **HTTP** hlavičce v poli Authorization
pomocí Bearer Token. Viz RFC 6750.

API token je generován každému uživateli při registraci a skládá se z 60 alfanumerických znaků. Uživatel ho poté nalezne na stránce *User settings*.

### Příklad HTTP hlavičky

    GET /api/v1/testcases/1 HTTP/1.1
    Host: localhost:8000
    Authorization: Bearer faQIfZxazJK5tYx40bbsEAG7G2Ab7zzlkLQ1PDp9BRF2I3RHRDkM7VSYP3Rj
    Cache-Control: no-cache

V případě POST a PUT je potřeba zahrnout i další pole:

    Content-Type: application/json

### Příklad cURL volání
    curl --request GET \
    --url http://localhost:8000/api/v1/testcases/1 \
    --header 'authorization: Bearer faQIfZxazJK5tYx40bbsEAG7G2Ab7zzlkLQ1PDp9BRF2I3RHRDkM7VSYP3Rj' \
    --header 'cache-control: no-cache' \

V případě nepovedené autentizace, je uživateli odeslána odpověď s návratovým HTTP
kódem **401** a obsahem:

```json
{
   "error" : "Unauthenticated."
}
```

## Test suites

    GET /api/v1/testsuites

### Popis
 Vrátí všechny testovací sady.

### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "TestSuiteGoals": "string",
    "TestSuiteVersion": "string",
    "TestSuiteDocumentation": "string",
    "href": "string"
}
```

---

    GET /api/v1/testsuites/{id}

### Popis
Vrátí vybranou testovací sadu

### Parametry
id - identifikator testovací sady

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
"TestSuite_id": "number",
"Name": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string",
"href": "string"
}
```

---

    POST /api/v1/testsuites

### Popis
Uloží testovací sadu do aplikace

### Vstup

```json
{
"*Name*": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string"
}
```
Poznámka: Atributy označené * jsou povinné
### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
"TestSuite_id": "number",
"Name": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string",
"href": "string"
}
```

2) **Neúspěch** - návratový kód: 400
Popis: V případě nezadaného jména, nebo v případě, kdy jméno překročí
maximální hranici 45 znaků

Obsah:

```json
{
"error": "Test suite name error"
}
```

---

    PUT /api/v1/testsuites/{id}

### Popis
 Edituje existující testovací sadu

### Vstup

```json
{
"Name": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string"
}
```

### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
"TestSuite_id": "number",
"Name": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string",
"href": "string"
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
"error": "Testsuite not foundr"
}
```

---

    DELETE /api/v1/testsuites/{id}

### Popis
Archvivuje danou testovací sadu

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
"success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
"error": "Testsuite not foundr"
}
```

## Test cases

    GET /api/v1/testcases

### Popis
Vrátí všechny testovací případy

### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

---

    GET /api/v1/testcases/{id}

### Popis
Vrátí vybraný testovací případ

### Parametry
id - identifikator testovacího případu

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

---

    GET /api/v1/testsuites/{idSuite}testcases

### Popis
Vrátí všechny testovací případy pro danou testovací sadu

### Parametry
id - identifikator testovací sady

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
"TestCase_id": "number",
"TestSuite_id": "number",
"Name": "string",
"IsManual": 0,
"TestCasePrefixes": "string",
"TestSteps": "string",
"ExpectedResult": "string",
"TestCaseSuffixes": "string",
"SourceCode": "string",
"TestCaseDescription": "string",
"Note": "string",
"href": "string"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: testovací sada nebyla nalezena

Obsah:

```json
{
"error": "Testsuit not found."
}
```
---


    POST /api/v1/testcases

### Popis
Uloží testovací případ do aplikace

### Vstup

```json
{
"*TestSuite_id*": "number",
"*Name*": "string",
"IsManual": 0,
"TestCasePrefixes": "string",
"TestSteps": "string",
"ExpectedResult": "string",
"TestCaseSuffixes": "string",
"TestCaseDescription": "string",
"Note": "string"
}
```
Poznámka: Atributy označené * jsou povinné

### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "TestCase_id": "number",
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "SourceCode": "string",
    "TestCaseDescription": "string",
    "Note": "string",
    "href": "string"
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě zadání neexistující TestSuite_id

Obsah:

```json
{
"error": "Test suite doesn't exists"
}
```

3) **Neúspěch** - návratový kód: 400

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:

```json
{
"error": "Test case name error"
}
```

    PUT /api/v1/testcases/{id}

### Popis
Edituje existující testovací případ

### Parametry
id - identifikator testovacího případu

### Vstup

```json
{
    "TestSuite_id": "number",
    "Name": "string",
    "IsManual": 0,
    "TestCasePrefixes": "string",
    "TestSteps": "string",
    "ExpectedResult": "string",
    "TestCaseSuffixes": "string",
    "TestCaseDescription": "string",
    "Note": "string"
}
```

### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
"TestSuite_id": "number",
"Name": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string",
"href": "string"
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nenalezení testovacího případu k editaci

Obsah:

```json
{
"error": "TestCase not found"
}
```

---

    DELETE /api/v1/testcase/{id}

### Popis
Archvivuje daný testovací případ

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
"success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
"error": "Testcase not found"
}
```

## Project

    GET /api/v1/projects

### Popis
Vrátí všechny projekty přiřazené danému uživateli

### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

    {
      "SUT_id": "number",
      "Name": "string",
      "ActiveDateFrom": "string",
      "ActiveDateTo": "string",
      "LastUpdate": "string",
      "ProjectDescription": "string",
      "TestingDescription": "string",
      "HwRequirements": "string",
      "SwRequirements": "string",
      "TestEstimatedDate": "string",
      "Note": "string",
      "Role" : "string"
    }

```

---

    GET /api/v1/projects/{id}

### Popis
Vrátí daný projekt uživateli

### Parametry
id - identifikátor projektu

### Odpovědi

1) **Úspěch**: návratový kód: 200

Obsah:

```json

{
  "SUT_id": "number",
  "Name": "string",
  "ActiveDateFrom": "string",
  "ActiveDateTo": "string",
  "LastUpdate": "string",
  "ProjectDescription": "string",
  "TestingDescription": "string",
  "HwRequirements": "string",
  "SwRequirements": "string",
  "TestEstimatedDate": "string",
  "Note": "string",
  "Role" : "string"
}

```

2) **Neúspěch** - návratový kód: 404

Popis: projekt nebyl nalezen

Obsah:

```json

{
    "error": "Project not found"
}

```

3) **Neúspěch** - návratový kód: 400

Popis: uživatel nemá práva k projektu

Obsah:

```json

{
    "error": "No rights to project"
}

```

---

    POST /api/v1/projects

### Popis
Uloží testovací případ do aplikace

### Vstup

```json
{
    "*Name*": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```
Poznámka: Atributy označené * jsou povinné

### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nezadání atributu Name či překročení maximální povolené velikosti 45 znaků

Obsah:

```json
{
"error": "SUT name error"
}
```

    PUT /api/v1/projects/{id}

### Popis
Edituje existující existujici projekt

### Parametry
id - identifikator projektu

### Vstup

```json
{
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
}
```

### Odpovědi

1) **Úspěch** - návratový kód: 201

Obsah:

```json
{
    "SUT_id": "number",
    "Name": "string",
    "ActiveDateFrom": "string",
    "ActiveDateTo": "string",
    "LastUpdate": "string",
    "ProjectDescription": "string",
    "TestingDescription": "string",
    "HwRequirements": "string",
    "SwRequirements": "string",
    "TestEstimatedDate": "string",
    "Note": "string",
}
```

2) **Neúspěch** - návratový kód: 400

Popis: V případě nenalezení testovacího případu k editaci

Obsah:

```json
{
"error": "SUT not found"
}
```

---


    DELETE /api/v1/projects/{id}

### Popis
Archvivuje daný projekt

### Parametry
id - identifikator projektu

### Odpovědi

1) **Úspěch** - návratový kód: 200

Obsah:

```json
{
"success": "Deleted"
}
```

2) **Neúspěch** - návratový kód: 404

Popis: V případě nenalezení testovací sady

Obsah:

```json
{
"error": "Project not found"
}
```

3) **Neúspěch** - návratový kód: 400

Popis: uživatel nemá práva k projektu

Obsah:

```json

{
    "error": "No rights to project"
}

```
