# Rest API pro Test-management-tool
(Verze 1.0)

## Přehled

Následující dokument poskytne dokumentaci k API k test-management-toolu, které je
postavené principu RESTu, mluvíme tedy o REST API.

V následujících částech bude dokumentován serializační formát, autentizace k API a dále jednotlivé
zdroje (resources), s nimiž jde pomocí API zacházet.

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

**Popis:** Vrátí všechny testovací sady.

**Odpovědi**:

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

**Popis:** Vrátí vybranou testovací sadu

**Parametry:** id - identifikator testovací sady

**Odpovědi**:

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

**Popis:** Uloží testovací sadu do aplikace

**Vstup:**

```json
{
"*Name*": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string"
}
```

**Odpovědi**:

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

**Popis:** Edituje existující testovací sadu
**Vstup:**

```json
{
"*Name*": "string",
"TestSuiteGoals": "string",
"TestSuiteVersion": "string",
"TestSuiteDocumentation": "string"
}
```

**Odpovědi**:

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

**Popis:** Archvivuje danou testovací sadu

**Odpovědi**:

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
