<div class="modal fade" id="myModal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<span class="modal-title">Additional Description / Calculation Work Sheet</span>
			</div>
			<div class="modal-body">
				<div id="ItemCodeDescModal"></div>
				<div class="row div12">
					<div>Title</div>
					<div><input type="text" name="txt_title" id="txt_title" class="tboxclass phold" placeholder=" Add your title here "/></div>
				</div>
				<div class="row div7">
					<div>Item Description</div>
					<div><textarea name="txt_item_desc_alt" id="txt_item_desc_alt" class="tboxclass phold" rows="1" style="width:99%" placeholder=" Add your item description (alternate description) here "></textarea></div>
				</div>
				<div class="row div5">
					<div>Quantity Description</div>
					<div><textarea name="txt_qty_dest_alt" id="txt_qty_dest_alt" class="tboxclass phold" rows="1" placeholder=" Add your quantity description here "></textarea></div>
				</div>
				<div>Calculation Description</div>
				<div><textarea name="txt_calc_desc" id="txt_calc_desc" class="tboxclass phold" rows="4" placeholder=" Add your calculation description here "></textarea></div>
				<div class="row clearrow"></div>
				<div><u>Calculation Sheet</u></div>
				<div class="row ModalCalcRow">
					<div class="div3 color-1" align="right">Amount ( &#8377 )&emsp;</div>
					<div class="div3 color-1" align="center">&nbsp;Action (&plus;, &minus;, &times;, &divide;, &percnt;)</div>
					<div class="div2 color-1" align="right">Action Factor &nbsp;&nbsp;</div>
					<div class="div3 color-1" align="right">Amount ( &#8377 )&nbsp;&nbsp;</div>
					<div class="div1" align="center">&nbsp;</div>
				</div>
				<div class="row ModalCalcRow NR-ROW" id="ModalCalcRow0">
					<div class="div3" align="center"><input type="text" name="txt_w_amt_modal[]" id="txt_w_amt_modal0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div3" align="center">
						<select name="cmb_action_modal[]" id="cmb_action_modal0" class="sboxsmclass ModalAction" data-index="0">
							<option value="">------ Select Action ------</option>
							<option value="A">Addition</option>
							<option value="M">Multiplication</option>
							<option value="S">Subtraction</option>
							<option value="D">Division</option>
							<option value="P">Percentage</option>
						</select>
					</div>
					<div class="div2" align="center"><input type="text" name="txt_action_factor_modal[]" id="txt_action_factor_modal0" class="sboxsmclass rtext ModalFactor" data-index="0"/></div>
					<div class="div3" align="center"><input type="text" name="txt_amount_modal[]" id="txt_amount_modal0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div1" align="center">
						<i style="font-size:24px" class="fa faicon-add modal-add-row" name="btn_add_modal" id="btn_add_modal0" data-index="0">&#xf01a;</i>
						<i style="font-size:24px" class="fa faicon-clr modal-clr-row" name="btn_clear_modal" id="btn_clear_modal0" data-index="0">&#xf05c;</i>
					</div>
				</div>
				<input type="hidden" name="modal_index" id="modal_index">
			</div>
			<div class="modal-footer">
				<div class="div7">
					<div class="div7 lboxlabel rtext color-1">New Rate&nbsp;( &#8377 )&nbsp;&nbsp;</div>
					<div class="div5"><input type="text" name="txt_new_final_rate" id="txt_new_final_rate" class="sboxsmclass rtext"></div>
				</div>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_save">Save</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_cancel">Close</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="myModalSD" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<span class="modal-title">Additional Description / Calculation Work Sheet</span>
			</div>
			<div class="modal-body">
				<div id="ItemCodeDescModalSD"></div>
				<div class="row div12">
					<div>Title</div>
					<div><input type="text" name="txt_title_sd" id="txt_title_sd" class="tboxclass phold" placeholder=" Add your title here "/></div>
				</div>
				<div class="row div12">
					<div>Item Description</div>
					<div><textarea name="txt_item_desc_alt_sd" id="txt_item_desc_alt_sd" class="tboxclass phold" rows="1" style="width:99%" placeholder=" Add your item description (alternate description) here "></textarea></div>
				</div>
				<div>Calculation Description</div>
				<div><textarea name="txt_calc_desc_sd" id="txt_calc_desc_sd" class="tboxclass phold" rows="4" placeholder=" Add your calculation description here "></textarea></div>
				<div class="row clearrow"></div>
				<div><u>Calculation Sheet</u></div>
				<div class="row ModalCalcRowSD">
					<div class="div2 color-1" align="right">Igcar Amt ( &#8377 )&emsp;</div>
					<div class="div2 color-1" align="right">TW Amt ( &#8377 )&emsp;</div>
					<div class="div2 color-1" align="center">&nbsp;(&plus;, &minus;, &times;, &divide;, &percnt;)</div>
					<div class="div1 color-1" align="right">Factor &nbsp;&nbsp;</div>
					<div class="div2 color-1" align="right">Igcar Amt ( &#8377 )&nbsp;&nbsp;</div>
					<div class="div2 color-1" align="right">TW Amt ( &#8377 )&nbsp;&nbsp;</div>
					<div class="div1" align="center">&nbsp;</div>
				</div>
				<div class="row ModalCalcRowSD NR-ROW-SD" id="ModalCalcRowSD0">
					<div class="div2" align="center"><input type="text" name="txt_w_igc_amt_modal[]" id="txt_w_igc_amt_modal0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div2" align="center"><input type="text" name="txt_w_ts_amt_modal[]" id="txt_w_ts_amt_modal0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div2" align="center">
						<select name="cmb_action_modal_sd[]" id="cmb_action_modal_sd0" class="sboxsmclass ModalActionSD" data-index="0">
							<option value="">---- Select ----</option>
							<option value="A">Addition</option>
							<option value="M">Multiplication</option>
							<option value="S">Subtraction</option>
							<option value="D">Division</option>
							<option value="P">Percentage</option>
						</select>
					</div>
					<div class="div1" align="center"><input type="text" name="txt_action_factor_modal_sd[]" id="txt_action_factor_modal_sd0" class="sboxsmclass rtext ModalFactorSD" data-index="0"/></div>
					<div class="div2" align="center"><input type="text" name="txt_igc_amount_modal_sd[]" id="txt_igc_amount_modal_sd0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div2" align="center"><input type="text" name="txt_ts_amount_modal_sd[]" id="txt_ts_amount_modal_sd0" class="sboxsmclass rtext" data-index="0" readonly=""/></div>
					<div class="div1" align="center">
						<i style="font-size:24px" class="fa faicon-add modal-add-row-sd" name="btn_add_modal_sd" id="btn_add_modal_sd0" data-index="0">&#xf01a;</i>
						<i style="font-size:24px" class="fa faicon-clr modal-clr-row-sd" name="btn_clear_modal_sd" id="btn_clear_modal_sd0" data-index="0">&#xf05c;</i>
					</div>
				</div>
				<input type="hidden" name="modal_index_sd" id="modal_index_sd">
			</div>
			<div class="modal-footer">
				<div class="div9">
					<div class="div3 lboxlabel rtext color-1">New IGCAR Rate&nbsp;( &#8377 )&nbsp;&nbsp;</div>
					<div class="div3"><input type="text" name="txt_new_igc_final_rate_sd" id="txt_new_igc_final_rate_sd" class="sboxsmclass rtext" readonly=""></div>
					<div class="div3 lboxlabel rtext color-1">New TW Rate&nbsp;( &#8377 )&nbsp;&nbsp;</div>
					<div class="div3"><input type="text" name="txt_new_ts_final_rate_sd" id="txt_new_ts_final_rate_sd" class="sboxsmclass rtext" readonly=""></div>
				</div>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_save_sd">Save</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="modal_cancel_sd">Close</button>
			</div>
		</div>
	</div>
</div>