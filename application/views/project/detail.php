<?php $this->load->view('header'); ?>

<div id="content">

  <div class="title">
    <h2>Projektinformationen</h2>
  </div>

  <div class="box">
    <h3>Beschreibung</h3>
    <div class="editInPlace"><?=nl2br($project['description']);?></div>
    <p></p>
    <h3>Versuche</h3>
    <table class="tableList">
      <thead>
        <tr>
          <th scope="col">Versuch</th>
          <th scope="col">Berechnungen</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
      <?php if (count($trials) > 0):?>
      <?php foreach($trials as $trial):?>
        <tr>
          <td><a href="<?=site_url('trials/'.$trial['id'])?>"title="Versuch &quot;<?=$trial['name']?>&quot; anzeigen"><?=$trial['name']?></a></td>
          <td><span class="active">Erfolgreich abgeschlossen</span></td>
          <td>
            <a href="<?=site_url('trials/results/'.$trial['id'])?>" title="Ergebnisse zum Versuch &quot;<?=$trial['name']?>&quot; anzeigen">Ergebnisse anzeigen</a> |
            <a href="<?=site_url('trials/edit/'.$trial['id'])?>" title="Versuch &quot;<?=$trial['name']?>&quot; bearbeiten">Bearbeiten</a> |
            <a href="<?=site_url('trials/delete/'.$trial['id'])?>" title="Versuch &quot;<?=$trial['name']?>&quot; entfernen">Entfernen</a>
          </td>
        </tr>
      <?php endforeach;?>
      <?php else:?>
        <tr>
          <td colspan="3">Keine Versuche vorhanden.</td>
        </tr>
      <?php endif;?>
      </tbody>
    </table>

    <p><a class="button add" href="<?=site_url('trials/create/'.$project['id'])?>">Neuen Versuch erstellen</a></p>
  </div>

  <div class="title">
    <h2>Letzte Berechnungen</h2>
  </div>

  <div class="box">
    <table class="tableList">
      <thead>
        <tr>
          <th scope="col">Versuch</th>
          <th scope="col">Fertiggestellt</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
      <?php if(count($jobsDone) > 0):?>
      <?php foreach($jobsDone as $job):?>
        <tr>
          <td>Versuchsname</td>
          <td>Heute, 09:32</td>
          <td>
            <a href="<?=site_url('trials/results/'.$trial['id'])?>" title="Ergebnisse zum Versuch &quot;<?=$trial['name']?>&quot; anzeigen">Ergebnisse anzeigen</a> |
            <a href="<?=site_url('trials/edit/'.$trial['id'])?>" title="Versuch &quot;<?=$trial['name']?>&quot; bearbeiten">Bearbeiten</a>
          </td>
        </tr>
      <?php endforeach;?>
      <?php else:?>
        <tr>
          <td colspan="3">Es wurden noch keine Berechnungen durchgeführt.</td>
        </tr>
      <?php endif;?>
      </tbody>
    </table>
  </div>

</div>

<?php $this->load->view('footer'); ?>
