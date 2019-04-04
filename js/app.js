


jQuery(document).ready(function($) {
	


	localDB.sync(remoteDB,{live:true,retry: true})
		.on('change', function (change) {
		  $(".sync").empty().append('<div title="'+change.change.docs_read+'" class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		})
		.on('paused', function (info) {
		  $(".sync").empty();
		  cIn();
		  console.log("paused")
		})
		.on('active', function (info) {
		  $(".sync").empty().append('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
		  console.log("active")
		})
		.on('error', function (err) {
		  console.log("err")
		  $(".sync").text("Synchronisation nicht möglich.")
		});
	$(document).on('click', '.suche', function(event) {
		event.preventDefault();
		console.log("Suche");
		q=$("#suche").val();
		suche(q);
	});

});

$(document).on('click', '.suche', function(event) {
		event.preventDefault();
		q=$("#suche").val();
		suche(q);
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

function pagination(direction, id){
	localDB.get(id)
	.then(function (doc){
		suche(doc.sig,10,direction)
	})
	.catch(function(err){console.log(err);});

}


if (window.addEventListener) 
   window.addEventListener("keydown", keycodes, false);
else if (window.attachEvent) 
   window.attachEvent("onkeydown", keycodes);
   
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


/*function cIn(){
	localDB.createIndex({
		index: {fields: ['sig']},
		name: "sig",
		ddoc: "sig"
	})
	.then(function(){
		localDB.createIndex({
		  index: {
		  	fields: ['tbkz']
		  },
		  name: "tbkz",
		  ddoc: "tbkz"
		})
	})
	.then(function (result){
		console.log("Index angelegt")
	})
	.then(function(){
		localDB.createIndex({
		  index: {
		  	fields: ['sig']
		  },
		  name: "sig",
		  ddoc: "sig"
		})
	})
	.then(function (result){
		console.log("Index angelegt")
	})
	.then(function(){
		localDB.createIndex({
		  index: {
		  	fields: ['tbkz','sig']
		  },
		  name: "tbkz-sig",
		  ddoc: "tbkz-sig"
		})
	})
	.then(function (result){
		console.log("Index angelegt")
	})
	.then(function(){
		localDB.createIndex({
		  index: {
		  	fields: ['sig','tbkz']
		  },
		  name: "sig-tbkz",
		  ddoc: "sig-tbkz"
		})
	})
	.then(function (result){
		console.log("Index angelegt")
	})
}
	*/
