<!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Dashboard Template · Bootstrap</title>

   
    <!-- Bootstrap core CSS -->
<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/build.css" rel="stylesheet">
<link href="css/dashboard.css" rel="stylesheet">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      table{
      	font-size: 1.2em;
      }
    </style>
    <!-- Custom styles for this template -->
  </head>
  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Inventura</a>
  <input id="suche" class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
  <button type="button" class="btn btn-light suche">Suche</button>
  <ul class="navbar-nav px-3 sync octicon octicon-database">
    	 <span class="octicon octicon-arrow-right"></span>
  </ul>
</nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active dbSelect" id="olkg" href="#">
              <span data-feather="database"></span>
              OLKG 
            </a>
          </li>
          <li class="nav-item" >
            <a class="nav-link dbSelect" id="provg" href="#">
              <span data-feather="database"></span>
              PrOVG 
            </a>
          </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Weiteres</span>
          <a class="d-flex align-items-center text-muted" href="#">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" id="recheck" href="#">
              <span data-feather="list"></span>
              Prüffälle
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <h2 id="h2i">OLKG-Inventurliste</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Signatur</th>
              <th>Autor</th>
              <th>Titel etc.</th>
              <th>Jahr</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody id="biblist">
            
           
          </tbody>
        </table>
        <div id="biblist2">
        </div>
      </div>
    </main>
  </div>
</div>        

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/pouchdb-7.0.0.min.js" type="text/javascript" charset="utf-8"></script>
<!--<script src="js/pouchdb.memory.js" type="text/javascript" charset="utf-8"></script>-->
<script src="js/handlebars-v4.1.1.js" type="text/javascript" charset="utf-8"></script>
<script src="js/pouchdb.find.js" type="text/javascript" charset="utf-8"></script>
<script src="js/feather.min.js"></script>
<script>
/*
var localDB = new PouchDB('ol');
var remoteDB = new PouchDB('http://localhost:5984/ol');
*/
var localDB = new PouchDB('ol');
var remoteDB = new PouchDB('http://localhost:5984/ol');

Handlebars.registerHelper('ifeq', function (a, b, options) {
    if (a == b) { return options.fn(this); }
    return options.inverse(this);
});

Handlebars.registerHelper('ifgt', function (a, b, options) {
    if (a > b) { return options.fn(this); }
    return options.inverse(this);
});

Handlebars.registerHelper('ifnoteq', function (a, b, options) {
    if (a != b) { return options.fn(this); }
    return options.inverse(this);
});




function suche(q,limit=10,direction="x"){
	//console.log(q)
	/*var crIn = new Promise(function(resolve,reject){
		cIn();
		resolve();
		});
	
	crIn.then(function(){
*/
	localDB.createIndex({
		index: {fields: ['sig']},
		name: "sig",
		ddoc: "sig"
	}).then(function(result){
		if(direction=="b"){
			docs =  localDB.find({
				   "selector": {
				      "sig": {
				         "$lt": q
				      }
				   },
				   "sort": [{"sig":"desc"}],
				   "limit": limit,
				   "use_index":"_design/sig"	
			}).then(function(docs){				
				q = docs.docs[docs.docs.length-1].sig			
				return localDB.find({
					   "selector": {
					      "sig": {
					         "$gt": q
					      }
					   },
					   "sort": [{"sig":"asc"}],
					   "limit": limit,
					   "use_index":"_design/sig"
				});				
			})
			return docs;
		}
		if(direction=="f"){
			return localDB.find({
				   "selector": {
				      "sig": {
				         "$gt": q
				      }
				   },
				   "sort": [{"sig":"asc"}],
				   "limit": limit,
				   "use_index":"_design/sig"
			})
		}
		else{
			return localDB.find({
				   "selector": {
				      "sig": {
				         "$gte": q
				      }
				   },
				   "sort": [{"sig":"asc"}],
				   "limit": limit,
				   "use_index":"_design/sig"
			})
		}
	}).then(function(d){renderList(d)})
	//})


}


jQuery(document).ready(function($) {
	feather.replace();


	
	

});

</script>
<script src="js/app.js" type="text/javascript" charset="utf-8"></script>
<script id="bibentry-template" type="text/x-handlebars-template">
	<tr id="{{_id}}">
	  	<td>{{sig}}<br>{{_design}}</td>
	  	<td>{{aut}}</td>
	  	<td>{{titel}}{{#if seq}}<br>{{seq}}{{/if}}{{#if gtitel}}<br>({{gtitel}}){{/if}}</td>
	  	<td>{{jahr}}</td>
	  	<td>
	  		<div class="form-check" >
			  <input class="form-check-input" type="radio" id="exampleRadios1{{_id}}" value="vorhanden" {{#ifeq checked 1}}{{#ifgt counter 0}}checked="checked"{{/ifgt}}{{/ifeq}}>
			  <label class="form-check-label" for="exampleRadios1{{_id}}">
			    Vorhanden
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="radio"  id="exampleRadios2{{_id}}" value="fehlt" {{#unless checked}}checked="checked"{{/unless}} {{#ifeq checked 0}}{{#ifeq counter 0}}checked="checked"{{/ifeq}}{{/ifeq}}>
			  <label class="form-check-label" for="exampleRadios2{{_id}}">
			    Fehlt
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="radio"  id="exampleRadios3{{_id}}" value="angebunden" {{#ifeq checked 2}}checked="checked"{{/ifeq}}>
			  <label class="form-check-label" for="exampleRadios3{{_id}}">
			    Angebunden
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" name="inlineRadioOptions" id="inlineRadio3{{_id}}" value="melden" {{#if recheck}}checked {{/if}}>
			  <label class="form-check-label" for="inlineRadio3{{_id}}">Melden</label>
			</div>
		</td>
	</tr>
</script>

<script id="recheck-template" type="text/x-handlebars-template">
	<tr id="{{_id}}">
	  	<td>{{sig}}</td>
	  	<td>{{aut}}</td>
	  	<td>{{titel}}{{#if seq}}<br>{{seq}}{{/if}}{{#if gtitel}}<br>({{gtitel}}){{/if}}</td>
	  	<td>{{jahr}}</td>
	</tr>
</script>

<script id="bibentry2-template" type="text/x-handlebars-template">
	<div class="card" id="{{_id}}">
	  <div class="card-body">
	    <p class="card-text">{{sig}}</p>
	    <h5 class="card-title">{{titel}}
	    	{{#if seq}}
	    		<br>{{seq}}
	    	{{/if}}
	    </h5>
	    {{#if aut}}
	    <h6 class="card-subtitle mb-2 text-muted">{{aut}}</h6>
	    {{/if}}
	    {{#if gtitel}}
	    <h6 class="card-subtitle mb-2 text-muted">{{gtitel}}</h6>
	    {{/if}}
	    <p class="card-text">{{jahr}}</p>
	    <div class="form-check form-check-inline" >
		  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
		  <label class="form-check-label" for="exampleRadios1">
		    Vorhanden
		  </label>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
		  <label class="form-check-label" for="exampleRadios2">
		    Fehlt
		  </label>
		</div>
		<div class="form-check form-check-inline">
		  <input class="form-check-input" type="checkbox" name="inlineRadioOptions" id="inlineRadio3" value="option3">
		  <label class="form-check-label" for="inlineRadio3">Melden</label>
		</div>
	    <button type="button" class="btn btn-light">Fehlt</button>
	    <button type="button" class="btn btn-light">Melden</button>
	  </div>
	</div>
<div ></div>
</script>


</html>