"""
Parses nespresso website for general information about the coffee cups.

The data parsed is stored in sqlite database for further use.
"""

from bs4 import BeautifulSoup as bs
# from lxml import html
import requests
import sqlite3 as sqlite
from pprint import pprint


def create_table():
    """Create table in sqlite database for each account."""
    print('Starting database connection')
    con = sqlite.connect('nespresso.sqlite')
    cur = con.cursor()

    sql = '''
            DROP TABLE IF EXISTS "flavours";
        '''
    cur.execute(sql)
    con.commit()

    sql = '''
            CREATE TABLE IF NOT EXISTS "flavours" (
                    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                    `flavour`   TEXT DEFAULT '',
                    `descr_title`   TEXT DEFAULT '',
                    `descr_text`    TEXT DEFAULT '',
                    `price` TEXT DEFAULT '',
                    `intensity` REAL,
                    `link`  TEXT DEFAULT '',
                    `image` TEXT DEFAULT ''
            )'''
    cur.execute(sql)
    con.commit()
    return (con, cur)


def parse_main():
    """Parse main page."""
    """Finds all flavours and retrieves the links for each flavour"""
    # Read page url into memory
    page = requests.get('https://www.nespresso.com/nl/nl/koffie-capsules-grands-crus', "lxml")
    page = bs(page.content, "lxml")

    data = list()

    info_content = page(class_="info_content")
    print("Found {} flavours".format(len(info_content)))
    for item in info_content:
        # Extract data from webpage
        flavour = item.find(flavour_filter).text
        print(flavour)

        try:
            descr_title = item.find(title_filter).text
        except:
            descr_title = ""
        print(descr_title)

        descr = item.find(descr_filter).text
        print(descr)

        price = item.find(class_='gc_price_percapsule').text
        print(price)

        try:
            intensity = item.find(class_='intensity-number').text
        except:
            intensity = ""
        print(intensity)

        page_link = 'https://www.nespresso.com{}'.format(item.find(page_link_filter)['href'])
        print(page_link)

        image = item.find(image_filter)['src']
        print(image)

        data.append((flavour, descr_title, descr, price, intensity, page_link, image))

    return data


def flavour_filter(tag):
    """Filter used in find_all function to filter out flavours."""
    if tag.parent.has_attr('class'):
        return(tag.name == 'h3' and 'info_text' in tag.parent['class'] and tag.parent.name == 'div')
    else:
        return False


def title_filter(tag):
    """Filter used in find_all function to filter out coffee description title."""
    if tag.parent.has_attr('class'):
        return(tag.name == 'span' and 'info_text' in tag.parent['class'] and tag.parent.name == 'div')
    else:
        return False


def descr_filter(tag):
    """Filter used in find_all function to filter out coffee description."""
    if tag.parent.has_attr('class'):
        return(tag.name == 'p' and 'info_text' in tag.parent['class'] and tag.parent.name == 'div')
    else:
        return False


def page_link_filter(tag):
    """Filter used in find_all function to filter out link to flavour page."""
    if tag.parent.has_attr('class'):
        return(tag.name == 'a' and 'info_text' in tag.parent['class'] and tag.parent.parent.name == 'div')
    else:
        return False


def image_filter(tag):
    """Filter used in find_all function to filter out link to flavour page."""
    if tag.parent.has_attr('class'):
        return(tag.name == 'img' and 'info_image' in tag.parent['class'])
    else:
        return False


def add_coffee(data):
    """Add coffee to the database."""
    global con, cur
    if len(data) > 1:
        for row in data:
            cur.execute("INSERT INTO flavours (flavour,descr_title,descr_text,price,intensity,link,image) VALUES (?,?,?,?,?,?,?)", row)
    elif len(data) == 1:
            row = data
            cur.execute("INSERT INTO flavours (flavour,descr_title,descr_text,price,intensity,link,image) VALUES (?,?,?,?,?,?,?)", row)
    con.commit()


def parse_product_page(link):
    """Parse each product page for further information."""
    link = 'https://www.nespresso.com{}'.format(link)
    page = requests.get(link)
    page = bs(page.content, "lxml")

    '''
    Only occurs on "Envivio Lungo" page
    col_l = page.find_all("div", class_="dp-cafe-values-colL")
    for element in col_l(class_="dp-cafe-value"):
        print("%s - %s" % (element.tag, element.text))
    '''

    '''
    Only occurs on "Envivio Lungo" page
    col_r = page(class_="dp-cafe-values-colR")
    for element in col_r("div", class_="dp-cafe-values-colR"):
        print("%s - %s" % (element.tag, element.text))
    '''


# Create table and connection and cursor object
con, cur = create_table()

# Parse main page
data = parse_main()

# Store parsed date in database
add_coffee(data)
