"""
Parses nespresso website for general information about the coffee cups.

The data parsed is stored in sqlite database for further use.
"""

from lxml import html
import requests
import sqlite3 as sqlite


def create_table():
    """Create table in sqlite database for each account."""
    print('Starting database connection')
    con = sqlite.connect('nespresso.sqlite')
    cur = con.cursor()

    sql = '''
            CREATE TABLE IF NOT EXISTS "flavours" (
                    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                    `flavour`   TEXT DEFAULT '',
                    `descr_title`   TEXT DEFAULT '',
                    `descr_text`    TEXT DEFAULT '',
                    `price` TEXT DEFAULT '',
                    `intensity` REAL,
                    `link`  TEXT DEFAULT ''
            )'''
    cur.execute(sql)
    con.commit()
    return (con, cur)


def add_coffee(data):
    """Add coffee to the database."""
    global con, cur
    if len(data) > 1:
        for row in data:
            cur.execute("INSERT INTO flavours (flavour,descr_title,descr_text,price,intensity,link) VALUES (?,?,?,?,?,?)", row)
    elif len(data) == 1:
            row = data
            cur.execute("INSERT INTO flavours (flavour,descr_title,descr_text,price,intensity,link) VALUES (?,?,?,?,?,?)", row)
    con.commit()

# Create table and connection and cursor object
con, cur = create_table()

# Read page url into memory
page = requests.get('https://www.nespresso.com/nl/nl/koffie-capsules-grands-crus')
tree = html.fromstring(page.content)

# Extract data from webpage
flavours = tree.xpath('//div[@class="info_text"]/h3/text()')
descr_title = tree.xpath('//div[@class="info_text"]/span/text()')
descr = tree.xpath('//div[@class="info_text"]/p/text()')
price = tree.xpath('//span[@class="gc_price_percapsule"]/text()')
intensity = tree.xpath('//div[@class="info_price"]/div[@class="intensity-block"]/div[@class="intensity-number"]/text()')
page_link = list(map('https://www.nespresso.com{}'.format, tree.xpath('//div[@class="info_text"]/a/@href')))

data = list(zip(flavours,
                descr_title,
                descr,
                price,
                intensity,
                page_link
                ))

add_coffee(data)
