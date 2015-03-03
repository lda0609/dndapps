<?php

class GodsController extends AppController
{

    var $uses = array('Gods', 'GodsDomains', 'Domains');

    function index()
    {
        if (!empty($this->data)) {
            $godDomains = $this->GodsDomains->find('list', array(
                'conditions' => array('dnd_gods_id' => $this->data['gods']['god']),
                'fields' => array('dnd_domains_id'),
                'order' => 'dnd_domains_id'));
            $this->set('god', $this->data['gods']['god']);
            $this->set('godDomains', $godDomains);
            $this->render('godsDomains');
        }
    }

    function save()
    {
        $godDomains = $this->GodsDomains->find('list', array(
            'conditions' => array('dnd_gods_id' => $this->data['god']['god']),
            'fields' => array('dnd_domains_id'),
            'order' => 'dnd_domains_id'));

        $addDomain = array_diff($this->data['god']['godDomains'], $godDomains);
        $removeDomain = array_diff($godDomains, $this->data['god']['godDomains']);
        foreach ($addDomain as $key => $domainId) {
            $newDomains['GodsDomains']['id'] = '';
            $newDomains['GodsDomains']['dnd_gods_id'] = $this->data['god']['god'];
            $newDomains['GodsDomains']['dnd_domains_id'] = $domainId;
            $this->GodsDomains->save($newDomains);
        }

        foreach ($removeDomain as $key => $domainId) {
            $deleteDomains = $this->GodsDomains->find('first', array(
                'conditions' => array(
                    'dnd_gods_id' => $this->data['god']['god'],
                    'dnd_domains_id' => $domainId),
                'fields' => array('id'),
            ));
            $this->GodsDomains->delete($deleteDomains['GodsDomains']['id']);
        }
        $this->redirect('index');
    }

    function consulta()
    {

        $this->GodsDomains->recursive = -1;
        $humanGodsDomains = $this->GodsDomains->find('all', array(
            'joins' => array(
                array('table' => 'gods',
                    'alias' => 'Gods',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Gods.id = GodsDomains.dnd_gods_id',
                    )
                )
            ),
            'conditions' => array('Gods.nonhuman' => '0'),
            'fields' => array('dnd_gods_id', 'dnd_domains_id'),
            'order' => array('Gods.name'),
        ));


        foreach ($humanGodsDomains as $key => $domain) {
            $godsDomainsFinalH[$domain['GodsDomains']['dnd_gods_id']][] = $domain['GodsDomains']['dnd_domains_id'];
        }
        foreach ($humanGodsDomains as $key => $domain) {
            $DomainGodsFinalH[$domain['GodsDomains']['dnd_domains_id']][] = $domain['GodsDomains']['dnd_gods_id'];
        }

        $this->GodsDomains->recursive = -1;
        $nonhumanGodsDomains = $this->GodsDomains->find('all', array(
            'joins' => array(
                array('table' => 'gods',
                    'alias' => 'Gods',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Gods.id = GodsDomains.dnd_gods_id',
                    )
                )
            ),
            'conditions' => array('Gods.nonhuman' => '1'),
            'fields' => array('dnd_gods_id', 'dnd_domains_id'),
            'order' => array('Gods.name'),
        ));


        foreach ($nonhumanGodsDomains as $key => $domain) {
            $godsDomainsFinalNH[$domain['GodsDomains']['dnd_gods_id']][] = $domain['GodsDomains']['dnd_domains_id'];
        }
        foreach ($nonhumanGodsDomains as $key => $domain) {
            $DomainGodsFinalNH[$domain['GodsDomains']['dnd_domains_id']][] = $domain['GodsDomains']['dnd_gods_id'];
        }



        $this->GodsDomains->recursive = -1;
        $GodsDomainsComp = $this->GodsDomains->find('all', array(
            'joins' => array(
                array('table' => 'gods',
                    'alias' => 'Gods',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Gods.id = GodsDomains.dnd_gods_id',
                    )
                )
            ),
            'fields' => array('dnd_gods_id', 'dnd_domains_id'),
            'order' => array('Gods.name'),
        ));


        foreach ($GodsDomainsComp as $key => $domain) {
            $godsDomainsComplete[$domain['GodsDomains']['dnd_gods_id']][] = $domain['GodsDomains']['dnd_domains_id'];
        }
        foreach ($GodsDomainsComp as $key => $domain) {
            $DomainGodsComplete[$domain['GodsDomains']['dnd_domains_id']][] = $domain['GodsDomains']['dnd_gods_id'];
        }

        $gods = $this->Gods->find('all', array('order' => 'name'));

        $this->set('godsDomainsH', $godsDomainsFinalH);
        $this->set('domainsGodsH', $DomainGodsFinalH);
        $this->set('godsDomainsNH', $godsDomainsFinalNH);
        $this->set('domainsGodsNH', $DomainGodsFinalNH);

        $this->set('godsDomainsC', $godsDomainsComplete);
        $this->set('domainsGodsC', $DomainGodsComplete);


        $this->set('gods', $gods);
    }

}

?>