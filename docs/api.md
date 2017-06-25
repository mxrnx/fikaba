# JSON API
Fikaba's json api tries to follow the [4chan api](https://github.com/4chan/4chan-API) wherever possible, and the [vichan api](https://github.com/vichan-devel/vichan-API/) after that. Do not assume they can be used interchangably though, because they still differ quite a lot.

## Endpoints
### Catalog
Returns a list with all original posts in it

http(s)://`siteurl`/api.php?mode=catalog

### Thread (res view)
Returns a list of all posts in a specific thread.

http(s)://`siteurl`/api.php?mode=res&res=`number`

Number should here be replaced by the post no. of the OP.

## Post object

| **attribute**   | **value**      | **description**                | **possible values**                        | **example value**           |
|:----------------|:---------------|:-------------------------------|:-------------------------------------------|:----------------------------|
| `no`            | `integer`      | Post number                    | 1-9999999999999                            | `9001`                      |
| `now`           | `text`         | Human-readable time            | text                                       | `16/10/24(Mon)00:04`        | 
| `name`          | `string`       | Name + trip/capcode            | text (may include html)                    | `someguy ## Admin`          |
| `email`         | `string`       | Email (or `sage`, `nonoko`)    | text                                       | `anon@example.com`, `nonoko`|
| `sub`           | `string`       | Subject                        | text                                       | `This is a subject`         |
| `com`           | `string`       | Comment                        | text (includes escaped HTML)               | `This is a comment`         |
| `ext`           | `string`       | File extension                 | .jpg, .png, .gif, .swf, .webm              | `.jpg`                      |
| `w`             | `integer`      | Image width                    | 0-10000                                    | `500`                       |
| `h`             | `integer`      | Image height                   | 0-10000                                    | `500`                       |
| `tim`           | `string`       | Renamed filename               | UNIX timestamp + microseconds              | `1344402680740`             |
| `time`          | `integer`      | UNIX timestamp                 | UNIX timestamp                             | `1344570123`                |
| `md5`           | `string`       | File MD5                       | text (24 character, packed base64 MD5 hash)| `NOetrLVnES3jUn1x5ZPVAg==`  |
| `fname`         | `string`       | Original filename              | text                                       | `lemonparty.png`            |
| `fsize`         | `integer`      | File size                      | 1-max filesize                             | `2500`                      |
| `root`          | `integer`      | Time when last modified        | UNIX timestamp                             | `1344571233`                |
| `resto`         | `integer`      | Response to                    | 0 (is a thread OP), 1-9999999999999        | `0`                         |
| `id`            | `text`         | User ID (empty if not enabled) | text                                       | `ng6ncCd.`
