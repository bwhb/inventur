function dbInit(){
	localDB.sync(remoteDB,{live:true,retry: true})
		.on('change', function (change) {
		  $(".sync").empty().append('<div title="'+change.change.docs_read+'" class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		})
		.on('paused', function (info) {
		  $(".sync").empty();
		//  console.log("paused")
		})
		.on('active', function (info) {
		  $(".sync").empty().append('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		  //console.log("active")
		})
		.on('error', function (err) {
		  console.log("err")
		  $(".sync").text("Synchronisation nicht möglich.")
		});
}

function renderList(d,templ="bib"){
	$("#biblist").empty()
		d.docs.forEach(function(e){
			while(e.sig.match(/(.*?)\b0+([1-9]+.*)/))e.sig = e.sig.replace(/(.*?)\b0+([1-9]+.*)/g,"$1$2")
			if(templ=="bib") var source   = document.getElementById("bibentry-template").innerHTML;
			if(templ=="recheck") var source   = document.getElementById("recheck-template").innerHTML;
			var template = Handlebars.compile(source);
			var context = e;
			var html    = template(context);
			$("#biblist").append(html);

			/*

			var source   = document.getElementById("bibentry2-template").innerHTML;
			var template = Handlebars.compile(source);
			var context = e;
			var html    = template(context);
			$("#biblist2").append(html);
			*/
		})


}

function pagination(direction, id){
	localDB.get(id)
	.then(function (doc){
		suche(doc.sig,10,direction)
	})
	.catch(function(err){console.log(err);});

}
function keycodes (e) {
	//console.log(e)
	if (e.key=="Enter")$(".suche").click()
	if (e.key=="ArrowRight"&& e.ctrlKey){
		if($("#biblist").children('tr').length>0)pagination("f",$("#biblist>tr").last().prop("id"));
	}
	if (e.key=="ArrowLeft"&& e.ctrlKey){
		if($("#biblist").children('tr').length>0)pagination("b",$("#biblist>tr").first().prop("id"));
	}
}

jQuery(document).ready(function($) {

	localDB.sync(remoteDB,{live:true,retry: true})
		.on('change', function (change) {
		  $(".sync").empty().append('<div title="'+change.change.docs_read+'" class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		})
		.on('paused', function (info) {
		  $(".sync").empty();
		  //console.log("paused")
		})
		.on('active', function (info) {
		  $(".sync").empty().append('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		  //console.log("active")
		})
		.on('error', function (err) {
		  //console.log("err")
		  $(".sync").text("Synchronisation nicht möglich.")
		});

});

$(document).on('click', '.suche', function(event) {
		event.preventDefault();
		q=$("#suche").val();
		suche(q);
	});
$(document).on('click', '#olkg', function(event) {
	event.preventDefault();
	localDB = new PouchDB('ol');
	remoteDB = new PouchDB('http://localhost:5984/ol');
	dbInit();
	$("#h2i").text("OLKG-Inventurliste")
	$(".dbSelect").removeClass('active');
	$(this).addClass("active");	
	})
.on('click', '#provg', function(event) {
	event.preventDefault();
	localDB = new PouchDB('pr');
	remoteDB = new PouchDB('http://localhost:5984/pr');
	dbInit();
	$("#h2i").text("PrOVG-Inventurliste")
	$(".dbSelect").removeClass('active');
	$(this).addClass("active");
	});

$(document).on('click', '#recheck', function(event) {
		event.preventDefault();
		localDB.createIndex({
			index: {fields: ['recheck','sig']},
			name: "recheck2",
			ddoc: "recheck2"
		}).then(function(result){
			return localDB.find({
				   "selector": {
				      "recheck": {
				         "$eq": true
				      }
				   },
				   "limit": 20
			})
		}).then(function(d){
			renderList(d,"recheck");
		});
});

$(document).on('change', '.form-check-input', function(event) {
	event.preventDefault();
	$(".form-check-input").prop({"disabled":"disabled"});
	c = $(this).val();
	id = $(this).parents("tr").prop("id");
	p = $(this).parents("tbody").children("tr").first().prop("id");
	console.log(id)
	if(id.length>=9){
		localDB.get(id)
			.then(function (doc){
				if(c=="vorhanden"){
					doc.checked = 1;
					doc.counter = 1;
				}
				if(c=="fehlt"){
					doc.checked = 0;
					doc.counter = 0;
				}
				if(c=="angebunden"){
					doc.checked = 2;
					doc.counter = 0;
				}
				if(c=="melden"){
					doc.recheck ? doc.recheck = false : doc.recheck = true;
				}
				doc.edited = new Date().toISOString();
				return doc;

			}).then(function(doc){
				localDB.put(doc)

			}).then(function(){
				localDB.get(id).then(function(doc){
				suche(doc.sig)
				})

			}).catch(function(err){console.log(err);});
	}	
});





if (window.addEventListener) 
   window.addEventListener("keydown", keycodes, false);
else if (window.attachEvent) 
   window.attachEvent("onkeydown", keycodes);
   
