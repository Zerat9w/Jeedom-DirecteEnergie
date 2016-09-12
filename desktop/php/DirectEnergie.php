<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'DirectEnergie');
$eqLogics = eqLogic::byType('DirectEnergie');
?>
<div class="row row-overflow">
    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un compteur}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes Compteurs}}
        </legend>
        <div class="eqLogicThumbnailContainer">
                      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-plus-circle" style="font-size : 7em;color:#94ca02;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;;color:#94ca02"><center>Ajouter</center></span>
    </div>
         <?php
                foreach ($eqLogics as $eqLogic) {
                    $opacity = '';
                    if ($eqLogic->getIsEnable() != 1) {
                    $opacity = '
                    -webkit-filter: grayscale(100%);
                    -moz-filter: grayscale(100);
                    -o-filter: grayscale(100%);
                    -ms-filter: grayscale(100%);
                    filter: grayscale(100%); opacity: 0.35;';
                    }
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                    echo "<center>";
                    echo '<img src="plugins/DirectEnergie/doc/images/DirectEnergie_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
                ?>
            </div>
    </div>   

    <div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <form class="form-horizontal">
            <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Nom de l'équipement Direct Energie}}</label>
                    <div class="col-lg-2">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Direct Energie}}"/>
                    </div>
					<label class="col-lg-1 control-label" >{{Objet parent}}</label>
                    <div class="col-lg-2">
                        <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
                            foreach (object::all() as $object) {
                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
					<label class="col-sm-2 control-label" ></label>
					<div class="col-sm-9">
						<input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
						<input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
					</div>
                </div>
				<legend><i class="fa fa-wrench"></i>  {{Configuration}}</legend>
                <div class="form-group">
                   <label class="col-lg-2 control-label">{{Compteur Téléinfo}}</label>
                    <div class="col-lg-2">
                        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="compteur" placeholder="{{Choisir le compteur Téléinfo}}"/>
						<a style="display : inline-block;margin:5px;"class="btn btn-default btn-xs cursor bt_selectCmdExpression" style="position : relative; top : 3px;" title="{{Rechercher une commande}}" id="ObjetTransmit"><i class="fa fa-list-alt"></i></a>
                    </div>
                </div>
            </fieldset> 
            
        </form>
         <div class="form-actions" align="right">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>

        <legend><i class="fa fa-list-alt"></i>  {{Tableau de commandes}}</legend>
       <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th style="width: 300px;">{{Nom}}</th><th>{{Type}}</th><th>{{Options}}</th><th>{{Actions}}</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>

<?php include_file('desktop', 'DirectEnergie', 'js', 'DirectEnergie'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
