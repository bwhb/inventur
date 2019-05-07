<tr id="{{id}}">
	<td>{{sig}}</td>
	<td>{{aut}}</td>
	<td>{{titel}}{{#if seq}}<br>{{seq}}{{/if}}{{#if gtitel}}<br>({{gtitel}}){{/if}}</td>
	<td>{{jahr}}</td>
	<td>
		<div class="form-check" >
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>
			<label class="form-check-label" for="exampleRadios1">
				Vorhanden
			</label>
		</div>
		<div class="form-check">
			<label class="form-check-label" for="exemplare">
				Anzahl
			</label>
			<input class="form-control" type="text" name="exemplare" id="exemplare" value="option1" checked>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option2">
			<label class="form-check-label" for="exampleRadios2">
				Fehlt
			</label>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="inlineRadioOptions" id="inlineRadio3" value="option3">
			<label class="form-check-label" for="inlineRadio3">Melden</label>
		</div>
	</td>
	<td>
		<button type="button" class="btn btn-light">Speichern</button>
	</td>
</tr>