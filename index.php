<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	
</body>
<script
  src="http://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script src="js/pouchdb-7.0.0.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/pouchdb.find.js" type="text/javascript" charset="utf-8"></script>
<script>
	var localDB = new PouchDB('marc21')
	var remoteDB = new PouchDB('http://localhost:5984/marc21');
	localDB.sync(remoteDB,{live:true,retry: true})
	.on('change', function (change) {
	  $("body").text(change.change.docs_read)
	}).on('paused', function (info) {
	  console.log("paused")
	  $("body").text("Synchronisation nicht erforderlich.")
	  // replication was paused, usually because of a lost connection
	}).on('active', function (info) {
	  console.log("active")
	  console.log(info)
	}).on('error', function (err) {
	  console.log("err")
	  console.log(err)
	  // totally unhandled error (shouldn't happen)
	});;
	
localDB.createIndex({
  index: {fields: ['sig']},
  ddoc: "sig"
})
.then(function(){
	localDB.find({
	selector:
	{sig: {$gt: '-C'}},
	sort: ['sig'],
	limit:250,
	use_index: 'sig'
	})
	.then(function(d){
		$("body").empty()
		d.docs.forEach(
			function(e){
				$("body").append("<p>"+e.sig.replace(/(.*)\n.*/,"$1"));
		})
	})
})
</script>
</html>