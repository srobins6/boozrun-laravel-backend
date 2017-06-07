<div class="modal" id="images-modal" tabindex="-1" role="dialog" aria-labelledby="images-modal-Label">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content panel panel-default">
			<div class="modal-header vertical-center push-apart panel-heading">
				<div class="h4 modal-title" style="flex:1 0;" id="images-modal-Label">@yield("modaltitle")</div>
				<div class="form-inline vertical-center push-apart">@yield("modalbuttons")
					<button type="button" class="close" data-dismiss="modal">
						<i class="fa fa-times fa-inverse"></i>
					</button>
				</div>
			</div>
			@yield("modalcontent")
		</div>
	</div>
</div>