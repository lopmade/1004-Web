# Add to php.ini
```
extension=mysqli
extension=php_openssl.dll
```

# TODO LIST
## DATABASE
* remove images on delete (if doing delete button)
## MARKET
* ~~should only show where item_status in items_listing table = 0 (not sold)~~

## MARKETITEM
### DELETE BUTTON IN MARKETITEM
* only on items that belong to user where item_status = 0 (not sold)
* delete from items_listing and item_offer tables

## PROFILE
### CHAT
* link to actual chat
### ACCEPT OFFER
* close chat
* ~~set item_status to 1~~
* add to transaction (if got time)
### ~~DECLINE OFFER~~
* ~~set offer_status in item_offer to 1 (rejected)~~
### ~~REMOVE FROM HISTORY (rejected offers)~~
* ~~delete from item_offer where offer_status=1 and offer_item_id = the one u clicked~~
### TRANSCATION HISTORY (if got time)
* show the items where item_status = 1
