<tr>
	<td class="active text-center">
		<?=isset($lan) ? $lan : '' ?>
	</td>
	<td class="text-center">
		<select name="BRANCH[]" class="form-control seBranch" onchange="checkBranch(this)">
			<option value="">선택하세요</option>
			<?if(isset($branchList)){
				foreach ( $branchList as $index => $row ){?>
					<option value="<?=$row['BRANCH']?>"><?=$row['BRANCH_NAME']?>(<?=$row['BRANCH']?>)</option>
			<?	}
			}?>
		</select>
	</td>
	<td class="text-center">

	</td>
	<td class="text-center">
		<input type="date" class="form-control" name="START_DATE[]" value="" />
	</td>
	<td class="text-center">
		<input type="date" class="form-control"	name="FINISH_DATE[]" value="" />
	</td>
	<td class="text-center">
		<input type="button" class="btn btn-default" value="삭제" onclick="deleteBranch(this)" />
	</td>
</tr>
