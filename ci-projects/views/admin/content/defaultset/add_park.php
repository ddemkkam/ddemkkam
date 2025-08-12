<table class="park_table park_table_<?=isset($id) ? $id : ''?> table table-bordered table-hover" style="margin-top: 10px;">
	<colgroup>
		<col width="35%">
		<col width="65%">
	</colgroup>
	<tr>
		<th class="text-center">타이틀</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="내부주차장"
				name="H_PARK_TITLE[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">위치</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_ADDRESS[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">설명1</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_DESC1[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">설명2</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_DESC2[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">설명3</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_DESC3[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">
			카카오 지도 html
			<br />
			(430 x 215)
			<br />
			카카오맵 > 공유하기 > HTML 태그 복사 ><br /> 소스생성하기 > 이미지지도
		</th>
		<td>
			<textarea
				class="form-control"
				name="H_PARK_KAKAO_MAP[]"
				style="height: 100px;"
			><?=isset($info['H_KAKAO_MAP_KEY']) ? $info['H_KAKAO_MAP_KEY'] : '' ?></textarea>
		</td>
	</tr>
	<tr>
		<th class="text-center">네이버</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_NAVER[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">카카오</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_KAKAO[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center">T MAP</th>
		<td>
			<input
				type="text"
				class="form-control"
				placeholder="s_2283b14e82"
				name="H_PARK_TMAP[]"
				value="<?=isset($info['H_PARK1_ADDRESS']) ? $info['H_PARK1_ADDRESS'] : '' ?>"
			>
		</td>
	</tr>
	<tr>
		<th class="text-center" colspan="2">
			<button type="button" class="btn btn-primary" id="deletePark" onclick="deleteParkFun('park_table_<?=isset($id) ? $id : ''?>')">삭제</button>
		</th>
	</tr>
</table>
