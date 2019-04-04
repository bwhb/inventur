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
