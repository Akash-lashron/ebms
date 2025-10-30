<style>
#navi1 {
    list-style:none inside;
    margin:0;
    padding:0;
    text-align:center;
	width:100%;
	background:#189DDC;
	min-height:30px;
    }

#navi1 li {
    display:block;
    position:relative;
    float:left;
    background: #189DDC; /* menu background color */
	padding-top: 0px !important;
	border:1px solid #0389C8;
    }

#navi1 li a {
    display:block;
    padding:0;
    text-decoration:none;
     /* this is the width of the menu items */
    line-height:28px; /* this is the hieght of the menu items */
    color:#ffffff; /* list item font color */
	font-family:sans-serif;
	padding:0px 15px;
	font-weight:bold;
	font-size:13px;
    }
        
#navi1 li li a {font-size:80%; width:200px;} /* smaller font size for sub menu items */
    
#navi1 li:hover {background:#0673A6;} /* highlights current hovered list item and the parent list items when hovering over sub menues */



/*--- Sublist Styles ---*/
#navi1 ul {
    position:absolute;
    padding:0;
    left:0;
    display:none; /* hides sublists */
	z-index:9999;
    }

#navi1 li:hover ul ul {display:none;} /* hides sub-sublists */

#navi1 li:hover ul {display:block;} /* shows sublist on hover */

#navi1 li li:hover ul {
    display:block; /* shows sub-sublist on hover */
    margin-left:200px; /* this should be the same width as the parent list item */
    margin-top:-35px; /* aligns top of sub menu with top of list item */
    }
</style>
<ul id="navi1">
  <li><a href="#">Masters</a>
    <ul>
      <li><a href="defaultvaluesmaster.php">Taxes & Overheads</a></li>
      <li><a href="item_master.php">Item Master</a></li>
      <li><a href="#">SUB SUB LIST &raquo;</a></li>
    </ul>
  </li>
  <li><a href="#">Data Sheet</a>
    <ul>
      <li><a href="#">Clearing And Earth Work</a>
	   <ul>
          <li><a href="AreaClearingCreate.php">Area Clearing</a>
		  <li><a href="#">Leveling the Area</a>
		  <li><a href="#">Clearing Light Jungle</a>
		  <li><a href="#">Clearing Heavy Jungle</a>
		  <li><a href="#">Cutting and Felling all types of Trees</a>
		  <li><a href="#">Earth Work Excavation</a>
		  <li><a href="#">Earth Work for Road Work</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			   <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
	  </li>
      <li><a href="#">Antitermite Treatment</a>
        <ul>
          <li><a href="#">Antitermite Treatment</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Concrete Items</a>
        <ul>
          <li><a href="#">Plain Cement Concrete</a>
		  <li><a href="#">Reinforced Cement Concrete</a>
		  <li><a href="#">Precast Units</a>
		  <li><a href="#">Reinforcement Steel</a>
		  <li><a href="#">Form Work</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Structural Steel Items</a>
        <ul>
          <li><a href="#">Structural Steel</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Expansion Joint</a>
        <ul>
          <li><a href="#">Expansion Joint</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Water/Weather Proofing</a>
        <ul>
          <li><a href="#">Water/Weather Proofing</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Masonry Items</a>
        <ul>
          <li><a href="#">Brick Work</a>
		  <li><a href="#">Solid Block Masonry</a>
		  <li><a href="#">RR Masonry</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  	  <li><a href="#">Flooring Items</a>
        <ul>
		  <li><a href="#">Soling</a>
          <li><a href="#">Damp Proof Course</a>
		  <li><a href="#">Cement Concrete Flooring</a>
		  <li><a href="#">Marble Flooring</a>
		  <li><a href="#">Kota Stone Flooring</a>
		  <li><a href="#">Granite Flooring</a>
		  <li><a href="#">Ceramic Flooring & Dado</a>
		  <li><a href="#">PVC Sheet Flooring</a>
		  <li><a href="#">Paver block & Wall Tile Cladding</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
      </li>
	  <li><a href="#">Plastering & Painting Works</a>
        <ul>
          <li><a href="#">Plastering</a>
		  <li><a href="#">Painting</a>
		  <li><a href="#">Polishing & Varnishing</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Wood Works</a>
        <ul>
          <li><a href="#">Flush Door Shutters</a>
		  <li><a href="#">Fittings</a>
		  <li><a href="#">locks</a>
		  <li><a href="#">Particle Board,Plywood & Lamination Sheets</a>
		  <li><a href="#">Vertical Blinds</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Rolling Shutters</a>
        <ul>
          <li><a href="#">Rolling Shutters</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Aluminium Works</a>
        <ul>
          <li><a href="#">Aluminium Works</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Glazing</a>
        <ul>
		  <li><a href="#">Sun Control Film</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		 <li><a href="#">False Ceiling</a>
        <ul>
		  <li><a href="#">False Ceiling</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		 <li><a href="#">Sanitary Installations</a>
        <ul>
		  <li><a href="#">Sanitary Installations</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
          <li><a href="#">Pipe Line Works</a>
        <ul>
		  <li><a href="#">Pipe Line Works</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Chambers</a>
        <ul>
		  <li><a href="#">Chambers</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Road Works</a>
        <ul>
		  <li><a href="#">Road Works</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>
		<li><a href="#">Dismantling & Demolishing Items</a>
        <ul>
		  <li><a href="#">Dismantling & Demolishing Items</a>
		  	<ul>
			  <li><a href="#">Create</a></li>
			  <li><a href="#">Edit</a></li>
			  <li><a href="#">Confirm</a></li>
			</ul>
		  </li>
        </ul>

		
	  
    </ul>
  </li>
  <li><a href="#">Main Item 3</a></li>
</ul>

