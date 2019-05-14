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
function h2create(id){
	if (id=="olkg")$("#h2i").text("OLKG-Inventurliste")
	if (id=="provg")$("#h2i").text("PrOVG-Inventurliste")
}
function renderList(d,templ="bib"){
	$("#biblist").empty()
	$(".table-responsive").show()
	$(".dbEditor").hide()
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
		//console.log(doc.sig.replace(/(.*?)[\[;].*/,"$1"))
		//suche(doc.sig.replace(/(.*?)[\[;].*/,"$1"),10,direction)
		console.log(doc.sig)
		suche(doc.sig,10,direction)
	})
	.catch(function(err){console.log(err);});

}
function keycodes (e) {
	//console.log(e)
	if (e.key=="Enter")$(".suche").click()
	if (e.key=="ArrowRight"&& e.ctrlKey && $(".active").hasClass("inventur")) {
		if($("#biblist").children('tr').length>0)pagination("f",$("#biblist>tr").last().prop("id"));
	}
	if (e.key=="ArrowLeft"&& e.ctrlKey && $(".active").hasClass("inventur")){
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
		$(".dbSelect.active").parent(".topNav").find(".nav-item").find(".active").removeClass('active')
		$(".dbSelect.active").parent(".topNav").find('.inventur').addClass('active')
		
		q=$("#suche").val();
		suche(q);
	});
$(document).on('click', '#olkg', function(event) {
	event.preventDefault();
	localDB = new PouchDB('ol');
	remoteDB = new PouchDB('https://mb21.hopto.org:6984/ol');
	dbInit();
	h2create("olkg");
	//$("#h2i").text("OLKG-Inventurliste")
	$(".active").removeClass('active');
	$(this).addClass("active");	
	$(this).parent("li").find(".inventur").addClass("active");
	$(".dbEditor").hide()
	$(".table-responsive").show()
	$("#biblist,#biblist2").empty()
	q=$("#suche").val();
	suche(q);
})
.on('click', '#provg', function(event) {
	event.preventDefault();
	localDB = new PouchDB('pr');
	remoteDB = new PouchDB('https://mb21.hopto.org:6984/pr');
	dbInit();
	//$("#h2i").text("PrOVG-Inventurliste")
	h2create("provg");
	$(".active").removeClass('active');
	$(this).addClass("active");
	$(this).parent("li").find(".inventur").addClass("active");
	$(".dbEditor").hide()
	$(".table-responsive").show()
	$("#biblist,#biblist2").empty()
	q=$("#suche").val();
	suche(q);
})
.on('click', '.inventur', function(event) {
	id = $(this).parents(".topNav").find(".dbSelect").prop("id");
	$("#"+id).click();
});



$(document).on('click', '.recheck', function(event) {
		event.preventDefault();
		$(".active").removeClass('active');
		$(this).parents(".topNav").find(".dbSelect").addClass('active')
		$(this).addClass("active")
		h2create($(this).parents(".topNav").find(".dbSelect").prop("id"))
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

$(document).on('click', '.dbEdit', function(event) {
	event.preventDefault();
	$(".active").removeClass('active');
	$(this).addClass("active")
	$(this).parents(".topNav").find(".dbSelect").addClass('active')
	h2create($(this).parents(".topNav").find(".dbSelect").prop("id"))
	$(".table-responsive").hide()
	$(".dbEditor").empty().show();
	source = document.getElementById("dbEditor-template").innerHTML
	var template = Handlebars.compile(source);
	var html    = template(template);
	$(".dbEditor").append(html);
})

$(document).on('change', '.form-check-input', function(event) {
	event.preventDefault();
	$(".form-check-input").prop({"disabled":"disabled"});
	c = $(this).val();
	elem = $(this).prop("id");
	id = $(this).parents("tr").prop("id");
	start = $("#biblist>tr").first().prop("id")
	p = $(this).parents("tbody").children("tr").first().prop("id");
	
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
				if(elem=="exemplare"){
					doc.checked = 1;
					doc.counter = c;
					console.log(c)
				}
				doc.edited = new Date().toISOString();
				return doc;

			}).then(function(doc){
				localDB.put(doc)

			}).then(function(){
				localDB.get(id).then(function(doc){				
					$("#suche").val(doc.sig.replace(/\b0+([1-9]+)\b/g,"$1"))
					console.log(doc.sig)
					suche(doc.sig)
				//suche(start)
				})

			}).catch(function(err){console.log(err);});
	}	
});

$(document).on("click","#dbEditSubmit",function(){
	event.preventDefault();
	console.log(123)
	var doc =  {};
	doc._id = "n"+Date.now();
	doc.aut = $("#aut").val()
	doc.titel = $("#tit").val()
	doc.jahr = $("#jahr").val()
	doc.sig = $("#sig").val()
	doc.checked = 0;
	doc.counter= 0;
	doc.recheck = true;
	localDB.info().then(function(info){
		doc.tbkz = info.db_name;
		console.dir(doc);
	}).then(function(){
		localDB.put(doc)
		.then(function(){
			localDB.createIndex({
				index: {fields: ['recheck','sig']},
				name: "recheck2",
				ddoc: "recheck2"
			})
		})
		.then(function(){
			return localDB.get(doc._id)
		})
		.then(function(doc){
			console.dir(doc);
			suche(doc.sig);
		})
	});
});




if (window.addEventListener) 
   window.addEventListener("keydown", keycodes, false);
else if (window.attachEvent) 
   window.attachEvent("onkeydown", keycodes);
   
