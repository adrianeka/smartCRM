# API Contract - Customer Data Management

Base URL lokal:

```text
http://127.0.0.1:8000/api/v1
```

Format umum response sukses:

```json
{
  "status": "success",
  "data": {}
}
```

Format umum response validasi Laravel:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

## Auth

Sebagian endpoint customer sudah tersedia untuk integrasi API. Jika deployment mengaktifkan proteksi
Sanctum penuh, client perlu login terlebih dahulu dan mengirim Bearer token.

```http
POST /login
Content-Type: application/json
```

Request:

```json
{
  "email": "admin@smartcrm.com",
  "password": "password"
}
```

## Customer List

```http
GET /customers
```

Query parameter:

| Parameter | Contoh | Keterangan |
| --- | --- | --- |
| `search` | `budi` | Cari kode, nama, email, telepon, perusahaan. |
| `status` | `Lead` | Filter status. |
| `company_name` | `Telkom` | Filter perusahaan. |
| `industry` | `Manufacturing` | Filter industri. |
| `city` | `Bandung` | Filter kota. |
| `customer_type` | `B2B` | Filter tipe customer. |
| `source` | `Campaign` | Filter sumber data. |
| `tag` | `VIP` | Filter tag. |
| `favorite` | `true` | Filter favorite. |
| `created_from` | `2026-06-01` | Filter tanggal mulai. |
| `created_to` | `2026-06-30` | Filter tanggal akhir. |
| `sort` | `created_at` | Kolom sorting. |
| `direction` | `desc` | Arah sorting. |
| `per_page` | `10` | Jumlah data per halaman. |

## Customer Detail

```http
GET /customers/{id}
```

Response berisi customer, tags, custom fields, dan attachments.

## Create Customer

```http
POST /customers
Content-Type: application/json
```

Request:

```json
{
  "customer_code": "CUST-001",
  "full_name": "Budi Santoso",
  "job_title": "Procurement Manager",
  "email": "budi@example.com",
  "website": "https://example.com",
  "phone": "081234567890",
  "whatsapp": "081234567890",
  "company_name": "PT Maju Bersama",
  "industry": "Manufacturing",
  "identity_number": "3276010101010001",
  "tax_number": "01.234.567.8-901.000",
  "gender": "male",
  "birth_date": "1995-01-15",
  "address": "Jl. Merdeka No. 10",
  "city": "Bandung",
  "province": "Jawa Barat",
  "postal_code": "40111",
  "country": "Indonesia",
  "status": "Lead",
  "customer_type": "B2B",
  "source": "Campaign",
  "lead_score": 80,
  "preferred_contact_method": "WhatsApp",
  "last_contacted_at": "2026-06-01 10:00:00",
  "next_follow_up_at": "2026-06-15 09:00:00",
  "notes": "Prospek prioritas",
  "is_favorite": true,
  "assigned_user_id": 2,
  "custom_fields": [
    {
      "field_key": "instagram",
      "field_type": "text",
      "field_value": "@budi"
    },
    {
      "field_key": "tanggal_kontrak",
      "field_type": "date",
      "field_date": "2026-06-20"
    },
    {
      "field_key": "prioritas",
      "field_type": "checkbox",
      "field_boolean": true
    }
  ]
}
```

## Update Customer

```http
PUT /customers/{id}
Content-Type: application/json
```

Field request sama seperti create. Untuk partial update dapat memakai field yang dibutuhkan sesuai
validasi controller.

## Delete Customer

```http
DELETE /customers/{id}
```

Response:

```json
{
  "status": "success",
  "message": "Data pelanggan berhasil dihapus!"
}
```

## Import Customer

```http
POST /customers/import
Content-Type: multipart/form-data
```

Form-data:

| Field | Tipe | Keterangan |
| --- | --- | --- |
| `file` | file | CSV, TXT, XLSX, atau XLS. |

Kolom minimal:

- `customer_code`
- `full_name`
- `email`
- `phone`
- `company_name`
- `status`

Template resmi:

```text
docs/customer-data-management/customer-import-template.csv
```

## Export Customer

```http
GET /customers/export/json
GET /customers/export/csv
GET /customers/export/excel
```

Output:

- JSON untuk integrasi service.
- CSV untuk spreadsheet sederhana.
- Excel untuk laporan/demo.

## Duplicate Detection

```http
GET /customers/duplicates?threshold=50
```

Response:

```json
{
  "status": "success",
  "data": [
    {
      "score": 80,
      "match_reasons": ["email", "phone"],
      "primary_candidate": {},
      "duplicate_candidate": {}
    }
  ]
}
```

Scoring:

- Email sama: 50
- Nomor telepon sama: 30
- Nama sama: 20
- Perusahaan sama: 10
- Maksimal score: 100

## Smart Merge

```http
POST /customers/{id}/merge
Content-Type: application/json
```

Request:

```json
{
  "duplicate_id": 12,
  "strategy": "prefer_complete",
  "field_values": {
    "phone": "081234567890"
  },
  "custom_fields": {
    "instagram": "@budi.official"
  }
}
```

Strategi:

- `prefer_complete`: memakai data yang paling lengkap.
- `prefer_primary`: mempertahankan data customer utama.
- `override`: memakai `field_values` dari request.

Efek merge:

- Data utama diperbarui.
- Custom fields digabung.
- Tags digabung.
- Attachments dipindahkan.
- Data duplikat dihapus.
- Activity log merge dibuat.

## Tags

```http
POST /customers/{id}/tags
Content-Type: application/json
```

Request:

```json
{
  "tag_ids": [1, 3, 4]
}
```

## Favorite

```http
PATCH /customers/{id}/favorite
```

Endpoint ini membalik status favorite customer.

## Activities

```http
GET /customers/{id}/activities
```

Mengembalikan activity log pelanggan secara pagination.

## Attachments

```http
GET /customers/{id}/attachments
POST /customers/{id}/attachments
GET /attachments/{id}/preview
GET /attachments/{id}/download
DELETE /attachments/{id}
```

Upload attachment:

```http
POST /customers/{id}/attachments
Content-Type: multipart/form-data
```

Form-data:

| Field | Tipe | Keterangan |
| --- | --- | --- |
| `file` | file | Maksimal 10 MB. |

## Error Code

| HTTP Code | Kondisi |
| --- | --- |
| 200 | Request berhasil. |
| 201 | Data berhasil dibuat. |
| 404 | Customer atau attachment tidak ditemukan. |
| 422 | Validasi gagal. |
| 500 | Error server/database. |
