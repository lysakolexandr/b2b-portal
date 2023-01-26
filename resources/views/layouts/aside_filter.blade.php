<aside class="col-lg-3">
	<div id="aside_filter" class="collapse card d-lg-block mb-5 ">

			<article class="filter-group">
			<div class="collapse show" id="collapse_aside4">
				<div class="card-body-on">
					<label class="form-check mb-2">
						<input class="form-check-input" type="checkbox" name="choose_x" id="only-available" {{ $available=='true' ? 'checked="checked"' : '' }}>
						<span class="form-check-label"> {{__('l.only_available')}} </span>
					</label>

				</div>
			</div>
			</article>
		<div class="d-grid my-2">
			<button class="btn btn-warning btn-sm mx-2" type="button" id="clean_filters"> {{__('l.to_clean')}} </button>
		</div>

	</div>
</aside>
