from pymarc import MARCReader
import re, couchdb, json

def xstr(s):
    if s is None:
        return ''
    return str(s)

def signieren(data):
    return data.group(1) + '{:05d}'.format(int(data.group(2))) +data.group(3)

couch = couchdb.Server('http://localhost:5984/')
db = couch['ppn']
docs = []
i = 0
# print("Verarbeite Titeldaten")
# with open('data/marc21/051-tit.mrc', 'rb') as fh:
#     reader = MARCReader(fh)
#     for record in reader:
#         dbEntry = {}
#         aufl =''
#         if record['100']:
#             aut = record['100']['a'] 
#         dbEntry['_id'] = record['001'].format_field()
        
#         if record['250']:
#             dbEntry['aufl'] = record['250']['a']
#         else:
#             dbEntry['aufl'] = ""
        
#         if record['362']:
#             dbEntry['seq'] = record['362']['a']
#         else:
#             dbEntry['seq'] = ""

#         dbEntry['jahr'] = record.pubyear()
#         dbEntry['titel'] = record.title()
        
#         if record['100']:
#             dbEntry['aut'] = record['100']['a']
#         else:
#             dbEntry['aut'] = ""

#         if record['773'] and record['245']:
#             dbEntry['gtitel'] = ' '.join([xstr(record['245']['a']),xstr(record['245']['n']),xstr(record['773']['q'])])
       
#         if record['773'] and record['490']:
#             dbEntry['gtitel'] = ' '.join([xstr(record['490']['a']),xstr(record['490']['v']),xstr(record['773']['q'])])
#         docs.append(dbEntry)
#         if i == 3000:
#             db.update((docs))
#             docs = []
#             i=0
#         i = i + 1
        

print("Verarbeite Lokaldaten")
with open('data/marc21/051-lok.mrc', 'rb') as fh:
    reader = MARCReader(fh)
    pr = []
    ol = []
    i = 0
    for record in reader:
        dbEntry = {}
        i = i+1
        if i % 1000 == 0:
            print (i)
        #print(record['004'].format_field())
        dbEntry['ppn'] = record['004'].format_field()
        dbEntry['_id'] = record['001'].format_field()

        if record['866']:
            dbEntry['seq'] = record['866']['a']
        
        for f in record.get_fields('852'):
            if f['c']:
                dbEntry['sig'] = re.findall(r'^[\-\#\+]*(.*)', f['c'])
                dbEntry['sig'] = dbEntry['sig'][0]
                dbEntry['sig'] = re.sub(r'(.*?\b)(\d{1,4})(\b.*)',signieren,dbEntry['sig'])
                #print (dbEntry['sig'])

        if re.search('Teilbestand.*(Preu|Oberlandeskultur)',record['852'].format_field()) or (record['935'] and re.search('pr|ol',record['935'].format_field())) :
            if not re.search('reichs',record['852'].format_field(), re.IGNORECASE) or not re.search(r'(par|ent|ads|zsn|nib|np|a 25|8\+|4\+|2\+)',xstr(dbEntry['sig']), re.IGNORECASE):
                if re.search('Oberlandeskultur',record['852'].format_field()):
                    dbEntry['tbkz'] = "ol"
                    doc = db.get(record['004'].format_field())
                    if doc:
                        dbEntry.update(doc)
                    ol.append(dbEntry)
                    if i % 1000 == 0:
                        #print(ol)
                        db = couch['ol']
                        db.update(ol)
                        ol = []

                else:
                    dbEntry['tbkz'] = "pr"
                    doc = db.get(record['004'].format_field())
                    if doc:
                        dbEntry.update(doc)
                    pr.append(dbEntry)
                    if i % 1000 == 0:
                        #print(pr)
                        db = couch['pr']
                        db.update(pr)
                        pr = []

                #print(dbEntry)

            else: 
                pass
