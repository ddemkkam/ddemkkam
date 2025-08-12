<? include $_SERVER['DOCUMENT_ROOT'].'views/admin/include/header.php'; ?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-body">
				<form id="ppeumEventFrm" >
					<div class="col-xs-12">
						<div class="col-xs-8">
							<table class="table table-bordered table-hover">
								<thead>
									<col style="width: 100px;" />
									<col style="width: 200px;" />
									<col style="width: 200px;" />
									<col style="width: 100px;" />
								</thead>
								<tbody>
									<tr>
										<th class="text-center">고객</th>
										<td colspan="3">
											<input name="user" class="btn btn-bitbucket" type="radio" value="tgkim2" checked />&nbsp;&nbsp;김태규
											&nbsp;&nbsp;&nbsp;&nbsp;
											<input name="user" class="btn btn-bitbucket" type="radio" value="tgkim10" />&nbsp;&nbsp;김태규10
											&nbsp;&nbsp;&nbsp;&nbsp;
											<input name="user" class="btn btn-bitbucket" type="radio" value="tgkim20" />&nbsp;&nbsp;김태규20
											&nbsp;&nbsp;&nbsp;&nbsp;
											<input name="user" class="btn btn-bitbucket" type="radio" value="tgkim30" />&nbsp;&nbsp;김태규30
										</td>
									</tr>
									<tr>
										<th class="text-center">카테고리</th>
										<td>
											<select id="m_category" class="form-control" >
												<option value=""></option>
												<?if ( isset($mainCategory) ) {
													foreach ( $mainCategory as $index => $row ) {?>
														<option value="<?=$row['uid']?>"><?=$row['name']?></option>
													<?}
												}?>
											</select>
										</td>
										<td>
											<select id="s_category" class="form-control">
												<option value=""></option>
												<?if ( isset($subCategory) ) {
													foreach ( $subCategory as $index => $row ) {
														foreach ( $row as $index2 => $row2 ) {?>
															<option class="v_i <?='v_'.$index?>" value="<?=$row2['uid']?>" style="display: none;"><?=$row2['name']?></option>
														<?}
													}
												}?>
											</select>
										</td>
										<td class="text-center">
											<input id="searchBtn" type="button" class="btn btn-bitbucket" value="검색">
										</td>
									</tr>
									<tr>
										<th class="text-center">상품</th>
										<td id="productView" colspan="3">

										</td>
									</tr>
									<tr>
										<th class="text-center">상담여부</th>
										<td colspan="3">
											<input type="radio" name="counsel" value="Y" style="width: 15px; height: 15px;" checked>&nbsp;&nbsp;상담
											&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="counsel" value="N" style="width: 15px; height: 15px;">&nbsp;&nbsp;미상담
											&nbsp;&nbsp;&nbsp;&nbsp;
										</td>
									</tr>
									<tr>
										<th class="text-center">날짜</th>
										<td colspan="3">
											<input type="date" id="searchDate" class="form-control" value="<?=date('Y-m-d')?>" style="width: 200px; float: left;">
											&nbsp;&nbsp;
											<input id="searchTime" type="button" class="btn btn-bitbucket" value="조회">
										</td>
									</tr>
									<tr>
										<th class="text-center">시간</th>
										<td colspan="3">
											<div id="timeView" class="col-xs-5" >

											</div>
										</td>
									</tr>
									<tr style="display: none;">
										<th class="text-center">메모</th>
										<td colspan="3">
											<input type="text" name="memo" class="form-control" value="" />
										</td>
									</tr>
									<tr>
										<th class="text-center"></th>
										<td colspan="3">
											<input id="res_btn" class="btn btn-bitbucket" type="button" value="예약" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>

<div id="resultDiv"></div>

<script>
	$(document).ready(function() {

		$("#m_category").change(function(){
			let m_val = $(this).val();

			$(".v_i").hide();

			$(".v_" + m_val).show();
		});

		$("#searchBtn").click(function(){
			let m_val = $("#m_category").val();
			let s_val = $("#s_category").val();

			// console.log(m_val);
			// console.log(s_val);
			$.ajax({
				url: "/testres/reservation/getApiProductList",
				data: { "category1" : m_val, "category2" : s_val },
				method: "GET",
				dataType: 'html',
				// dataType: 'json',
				success:function(data){
					// console.log(data);
					$("#productView").html(data);
				}
				,fail:function(xhr, status, errorThrown) {
					console.log("오류가 발생하였습니다.<br>");
					console.log("오류명: " + errorThrown + "<br>");
					console.log("상태: " + status);
				}
			});
		});

		$("#searchTime").click(function(){
			let searchDate = $("#searchDate").val();
			let searchCounsel = $("input[name='counsel']:checked").val();

			let productArr = new Array();
			$(".pro_checkbox").each(function () {
				if ( $(this).is(":checked") == true ) {
					productArr.push($(this).val());
				}
			});

			// console.log(productArr);
			// return false;
			$.ajax({
				url: "/testres/reservation/getRoomTimeData",
				data: { "date" : searchDate, "is_counsel" : searchCounsel, "treatment_shop" : productArr },
				method: "GET",
				dataType: 'html',
				// dataType: 'json',
				success:function(data){
					//console.log(data);
					$("#timeView").html(data);
				}
				,fail:function(xhr, status, errorThrown) {
					console.log("오류가 발생하였습니다.<br>");
					console.log("오류명: " + errorThrown + "<br>");
					console.log("상태: " + status);
				}
			});
		});


		$("#res_btn").click(function(){
			let res_data = new Array();
			let productArr = new Array();
			$(".pro_checkbox").each(function () {
				if ( $(this).is(":checked") == true ) {
					productArr.push($(this).val());
				}
			});


			let public_ci = $("input[name='user']:checked").val();
			let reserve_type = '온라인광고';
			let reserve_date = $("#searchDate").val();;
			let reserve_time = $("input[name='res_time']:checked").data("time");
			let room_seq = $("input[name='res_time']:checked").val();
			let is_counsel = $("input[name='counsel']:checked").val();;
			let treament_shop = productArr;
			let memo = $("input[name='memo']").val();

			// return false;
			$.ajax({
				url: "/testres/reservation/setReservation",
				data: {
					 "public_ci" : public_ci
					,"reserve_type" : reserve_type
					,"reserve_date" : reserve_date
					,"reserve_time" : reserve_time
					,"room_seq" : room_seq
					,"is_counsel" : is_counsel
					,"treament_shop" : treament_shop
					,"memo" : memo
				},
				method: "POST",
				dataType: 'html',
				// dataType: 'json',
				success:function(data){
					console.log(data);
					$("#resultDiv").html(data);
				}
				,fail:function(xhr, status, errorThrown) {
					console.log("오류가 발생하였습니다.<br>");
					console.log("오류명: " + errorThrown + "<br>");
					console.log("상태: " + status);
				}
			});

		});



	})
</script>
