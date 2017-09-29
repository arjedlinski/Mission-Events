<?php 
namespace mcsc\MissionBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use mcsc\MissionBundle\Entity\mission;
use mcsc\MissionBundle\Entity\missionSection;
use mcsc\MissionBundle\Entity\missionSlots;
use mcsc\MissionBundle\Form\Type\MissionInfo;
use mcsc\MissionBundle\Form\Type\MissionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use mcsc\MissionBundle\Entity\Users;
use Symfony\Component\Stopwatch\Section;
use mcsc\MissionBundle\Entity\Logs;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;


class MissionController extends Controller
{
	
	public function joinSlotAction($slot_id, $mission_id)
	{
		$this->checkNumberOfMission();
		$response = new JsonResponse();
		$user = new Users();
		$user = $this->getUser();
		$em = $this->getDoctrine()->getEntityManager();

		$mission_em = $em->getRepository('mcscMissionBundle:mission')->find($mission_id);
		if ($mission_em == NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak wydarzenia, na którą próbowano się zapisać. Prawdopodobnie zostało usunięte.",
					'error_code' => 1
			));
			return $response;
		}

		$slot_em  = $em->getRepository('mcscMissionBundle:missionSlots')->find($slot_id);
		
		if ($slot_em == NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak miejsca, na którą próbowano się zapisać. Prawdopodobnie zostało usunięte.",
					'error_code' => 6
			));
			return $response;
		}
		
		if ($slot_em->getUserId() == $user->getIdUser())
		{
			$response->setData(array(
				'result' => "ok"));
		
			return $response;
		}
		
		if (time() >= $mission_em->getDeadline()->getTimestamp())
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Nie można zapisać się, ponieważ czas na to dobiegł końca.",
					'error_code' => 2
			));
			return $response;
		}
		
		if ($slot_em->getPlayer() != NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Nie można zapisać się, ponieważ to miejsce jest już zajęte.",
					'error_code' => 3
			));
			return $response;
		}

		if ($this->checkIsJoinedThisMission($user->getIdUser(), $mission_id))
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Zajmujesz już inne miejsce na tym wydarzeniu.",
					'error_code' => 4
			));
			return $response;
		}
		
		$slot_em->setPlayer($user->getUsername());
		$slot_em->setUserId($user->getIdUser());
		$mission_em->setFreeSlots($mission_em->getFreeSlots()-1);
		$user->setSlotsMissionNumber(($user->getSlotsMissionNumber())+1);
		$em->flush();
		$this->checkSlotNumber();

		$response->setData(array(
				'result' => "ok"));
		
		return $response;
		
	}
	
	
	
	public function exitSlotAction($slot_id, $mission_id, Request $request)
	{
		$this->checkNumberOfMission();
		$response = new JsonResponse();
		$user = new Users();
		$user = $this->getUser();
		$em = $this->getDoctrine()->getEntityManager();
		$mission_em = $em->getRepository('mcscMissionBundle:mission')->find($mission_id);
		if ($mission_em == NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak wydarzenia, z którego próbowano się wypisać. Prawdopodobnie zostało usunięte.",
					'error_code' => 1
			));
			return $response;
		}
		
		if (time() >= $mission_em->getDeadline()->getTimestamp())
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Nie można wypisać się, ponieważ czas na to dobiegł końca.",
					'error_code' => 2
			));
			return $response;
		}
		
		$slot_em = $em->getRepository('mcscMissionBundle:missionSlots')->find($slot_id);
		if ($slot_em == NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak miejsca, z którego próbowano się wypisać. Prawdopodobnie zostało usunięte.",
					'error_code' => 6
			));
			return $response;
		}
		
		if ($slot_em->getUserId() != $user->getIdUser())
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak uprawnień do wypisania innych osób.",
					'error_code' => 3
			));
			return $response;
		}
		
		$slot_em->setPlayer(NULL);
		$slot_em ->setUserId(NULL);
		$mission_em->setFreeSlots($mission_em->getFreeSlots()+1);
		$user->setSlotsMissionNumber(($user->getSlotsMissionNumber())-1);
		$em->flush();
		$this->checkSlotNumber();
		
		$response->setData(array(
				'result' => "ok"));
		
		return $response;
	
	}
	
	
	
	public function removeAction($mission_id)
	{
		$response = new JsonResponse();
		$this->checkNumberOfMission();
		$this->checkSlotNumber();
		$user = $this->getUser();
		$em = $this->getDoctrine()->getEntityManager();
		$mission_em = $this->getDoctrine()->getRepository('mcscMissionBundle:mission')->find($mission_id);
		
		if (!($this->get('security.context')->isGranted('ROLE_ADMIN')) && $user->getIdUser() != $mission_em->getAuthorId())
		{
			$response->setData(array(
						'result' => "error",
						'error_msg' => "Brak uprawnień do usuwania misji innych osób.",
						'error_code' => 7));
				
			
			
			$error = 'Próba usunięcia nie swojej misji';
			$this->createLog($error , $user->getIdPassword());
			return $response;
		}
		$em->remove($mission_id);
		$em->flush();
		
		$response->setData(array(
				'result' => "ok"));
		
		return $response;
	}
	
	
	
	public function showMissionAction($mission_id)
	{
		$response = new JsonResponse();
		$this->checkNumberOfMission();
		$this->checkSlotNumber();
		
		$user = new Users();
		$user = $this->getUser();
		
		$mission_em = $this->getDoctrine()
		->getRepository('mcscMissionBundle:mission')
		->find($mission_id);
		if ($mission_em == NULL)
		{
			$response->setData(array(
					'result' => "error",
					'error_msg' => "Brak wydarzenia. Prawdopodobnie została usunięte.",
					'error_code' => 1
			));
			return $response;
		}
		
		if (time() >= $mission_em->getDeadline()->getTimestamp()) $after_deadline = true;
		else $after_deadline = false;
		
		$repository = $this->getDoctrine()
		->getRepository('mcscMissionBundle:missionSection');
		
		$query = $repository->createQueryBuilder('m')
		->select('m.section_id, m.section_name')
		->where('m.missionSection = ' .$mission_id)
		->getQuery();
		$section_e = $query->getResult();
		$isjoin = $this->checkIsJoinedThisMission($user->getIdUser(), $mission_id);
		
		$i = 0;
		$slots = array();
		
		//TODO: Za dużo wcięć, do poprawy.
		foreach ($section_e as $section)
		{
			$slots[$i]['section_id'] = $section['section_id'];
			$slots[$i]['section_name'] = $section['section_name'];
			
			$repository = $this->getDoctrine()->getRepository('mcscMissionBundle:missionSlots');
			$query = $repository->createQueryBuilder('s')
			->select('s.slot_id,s.function,s.player,s.user_id')
			->where('s.missionSection = ' . $section['section_id'])
			->getQuery();
			$slot_e = $query->getResult();
			$j=0;
			
			foreach ($slot_e as $slot)
			{
				$slots[$i]['members'][$j]['slot_id'] = $slot['slot_id'];
				$slots[$i]['members'][$j]['function'] = $slot['function'];
				$slots[$i]['members'][$j]['player'] = $slot['player'];
				$slots[$i]['members'][$j]['user_id'] = $slot['user_id'];
				
				if ($slot['player'] == NULL AND $isjoin == false AND $after_deadline == false)
				{
					$slots[$i]['members'][$j]['can_join'] = true;
					$slots[$i]['members'][$j]['join_url'] = 
					$this->get('router')->generate('join_slot', array(
							'mission_id' => $mission_id,
							'slot_id' => $slot['slot_id']));
				}
				else 
				{
					$slots[$i]['members'][$j]['can_join'] = false;
					if ($user->getIdUser() == $slot['user_id'])
					{
						$slots[$i]['members'][$j]['entered'] = true;
						if ($after_deadline) $slots[$i]['members'][$j]['can_exit'] = false;
						else 
						{
							$slots[$i]['members'][$j]['can_exit'] = true;
							$slots[$i]['members'][$j]['exit_url'] =
							$this->get('router')->generate('exit_slot', array(
									'mission_id' => $mission_id,
									'slot_id' => $slot['slot_id']));
						}
						
					}
				}
				$j++;
			}
			$i++;
		}

		
		$response->setData(array(
				'result' => "ok",
				'data' => $slots,
				'time' => time()
				
		));
		
		return $response;
	}
	
	
    public function newAction(Request $request)
    {
    	
    	$em = $this->getDoctrine()->getEntityManager();
		$checkdata = $em->getRepository('mcscMissionBundle:mission')->findAll();	
    	$mission = new mission();
    	$mission->setStartDate(new \DateTime());
    	$mission->setDeadline(new \DateTime());
    	$missionslot = new missionSlots();
    	$missionSection = new missionSection();
		$user = $this->getUser();
    	$form = $this->createForm(new MissionInfo(), $mission)
    	->add('Dodaj', 'submit');
    	$form->handleRequest($request);
    	$this->checkNumberOfMission();
    	$this->checkSlotNumber();
    	
    	if($user->getMissionNumber() >= 4 AND !$this->get('security.context')->isGranted('ROLE_ADMIN'))
    	{
    		$this->addFlash(
    				'danger',
    				'Osiągnąłeś limit otwartych wydarzeń – nie możesz dodać więcej.'
    		);
    		return $this->redirect($this->generateUrl('events'));
    	}
    	
    	if ($form->isValid())
    	{
//			To raczej nie ma sensu bo mogą być trenigni
//     		foreach($checkdata as $test)
//     		{
//     			if ($test->getStartDate()->format('Y-m-d') == $mission->getStartDate()->format('Y-m-d') )
//     			{ 
//     				return $this->render('mcscMissionBundle:Default:404.html.twig',array('error' => 'Na ten dzień jest już misja na zapisy. Wybierz inny termin.'));
//     			}
//     		}
    		$mission->setAuthor($user->getUsername());
    		$mission->setAuthorId($user->getIdUser());
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($mission);
    		$em->flush();
    		$id = $mission->getmission_id();
    		$query = $em->createQuery('SELECT IDENTITY(slot.missionSection) AS slot_section_id,
		 IDENTITY(section.missionSection) AS section_mission_id
		 FROM mcscMissionBundle:missionSlots slot
		 INNER JOIN mcscMissionBundle:missionSection section WITH section.section_id = slot.missionSection
		 INNER JOIN mcscMissionBundle:mission mission WITH mission.mission_id = section.missionSection
		 WHERE mission.mission_id =
		 '.$id);

    		$mission->setNumberOfSlots(count($query->getResult()));
    		$mission->setFreeSlots(count($query->getResult()));
    		$em->persist($mission);
    		$em->flush();

    		$this->addFlash(
    				'success',
    				'Wydarzenie zostało dodane.'
    		);
    		
    		return $this->redirect($this->generateUrl('events'));
    	}
    		
    	return $this->render('mcscMissionBundle:Default:mission_edit.html.twig', array(
    				
    			'form' => $form->createView()
    	));
    }
    
    
    public function AjaxEventListAction(Request $request)
    {
    	
    	$response = new JsonResponse();
    	$this->checkSlotNumber();
    	$date = new \DateTime();
    	$date = $date->format('Y-m-d H:i:s');
    	
    	$em = $this->getDoctrine()->getManager();
    	$mission_em = $em->getRepository('mcscMissionBundle:mission');
    	$query = $mission_em->createQueryBuilder('mission');

    	
    	if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
    	{
    		if ($request->request->get('show_only_my') OR $request->query->get('show_only_i_entered'))
    		{
    			$response->setData(array(
    					'result' => "error",
    					'error_msg' => "Opcja dostępna tylko dla zalogowanych",
    					'error_code' => 8
    					
    			));
    	
    			return $response;
    		}
    		$user = NULL;
    	}
    	else 
    	{
    		$user = $this->getUser();		
    		
    		if ($request->request->get('show_only_my'))
    		{
    			$query = $query->andWhere('mission.author_id = :author_id')
    			->setParameter('author_id', $user->getIdUser());
    		}
    		
    		if ($request->request->get('show_only_i_entered'))
    		{
    			$query = $query->innerJoin('mcscMissionBundle:missionSection', 'section', 'WITH', 'mission.mission_id = section.missionSection')
    			->innerJoin('mcscMissionBundle:missionSlots', 'slots', 'WITH', 'section.section_id = slots.missionSection')
    			->andWhere('slots.user_id = :player_id')
    			->setParameter('player_id', $user->getIdUser());
    		}
    	}
    	
    	
    	if ($request->request->get('show_only_open'))
    	{

    		$query = $query->andWhere('mission.deadline > :date')
    			->setParameter('date', $date);
    	}
    	
    	if ($request->request->get('show_not_started'))
    	{

    		$query = $query->andWhere('mission.start_date > :date')
    			->setParameter('date', $date);
    	}
    	//TODO: Limit stron po stronie serwera
    	$query = $query->setFirstResult(0)
    			->setMaxResults(30)
    			->orderBy('mission.start_date', 'ASC');
    	$query = $query->getQuery();

    	$events_e = $query->getResult();

    	if ($events_e == NULL) 
    	{
    		$response->setData(array(
    				'result' => "error",
    				'error_msg' => "Brak wydarzeń dla podanych parametrów.",
    				'error_code' => 1
    		));
    		
    		return $response;
    	}
    	
		$i = 0;
		$events = array();

    	foreach ($events_e as $event)
    	{
    		if (isset($user))
    		{
    			if ($event->getAuthorId() == $user->getIdUser()) $events[$i]['can_edit'] = true;
    			elseif ($this->get('security.context')->isGranted('ROLE_ADMIN')) $events[$i]['can_edit'] = true;
    		}

    		$events[$i]['mission_id'] = $event->getmission_id();
			$events[$i]['game'] = $event->getGame()->getName(); 
			$events[$i]['id'] = $event->getMissionId();
			$events[$i]['name'] = $event->getName();
    		$events[$i]['mods'] = $event->getMods()->getName();
    		$events[$i]['type'] = $event->getType()->getName();
    		$events[$i]['author'] = $event->getAuthor();
    		$events[$i]['author_id'] = $event->getAuthorId();
    		$events[$i]['start_date']['human'] = $event->getStartDate()->format('Y-m-d H:i:s');
    		$events[$i]['start_date']['timestamp'] = $event->getStartDate()->getTimestamp();
			$events[$i]['dead_line']['human'] = $event->getDeadline()->format('Y-m-d H:i:s');;
    		$events[$i]['dead_line']['timestamp'] = $event->getDeadline()->getTimestamp();
    		$events[$i]['free_slots'] = $event->getFreeSlots();
    		$events[$i]['slots'] = $event->getNumberOfSlots(); 
    		$events[$i]['url'] = $this->get('router')->generate('view_event', array(
    				'event_id' => $event->getmission_id()));
    		$events[$i]['edit_url'] = $this->get('router')->generate('event_edit', array(
    				'event_id' => $event->getmission_id()));
    		$i++;
    	}
    	
    	$response->setData(array(
    			'result' => "ok",
    			'events' => $events,
    			'time' => time()
    	
    	));

    	return $response;

    }
    
    public function viewAction($event_id)
    {
    	return $this->render('mcscMissionBundle:Default:event.html.twig',
    	array(
    			'event_id' => $event_id,
				));

    }
    
    public function listAction($show_only_my = false, $show_only_open = false, $show_only_i_entered = false, $show_not_started = true)
    {
    	return $this->render('mcscMissionBundle:Default:list_events.html.twig',
    			array(
    					'show_only_my' => $show_only_my,
    					'show_only_open' => $show_only_open,
    					'show_only_i_entered' => $show_only_i_entered,
    					'show_not_started' => $show_not_started,
    			));
    
    }
    
    public function indexAction(Request $request)
    {
    	$this->checkNumberOfMission();
    	$this->checkSlotNumber();
    	return $this->render('mcscMissionBundle:Default:index.html.twig');
    			
    }
    
    
    public function eventEditAction($event_id ,Request $request)
    {
    	
    	$user = new Users();
    	$user = $this->getUser();
    	$this->checkNumberOfMission();
    	$this->checkSlotNumber();
    	$em = $this->getDoctrine()->getManager();
    	$mission = $em->getRepository('mcscMissionBundle:mission')->find($event_id);
    	if (!$mission) {
      		return new Response(
              'Nie ma wydarzenia o ID: ' . $event_id);
    	}
    	
    	$datatest = $mission->getStartDate()->format('Y-m-d H:i:s');
    	$userid = $mission->getAuthorId();
    	$em = $this->getDoctrine()->getManager();
    	//zapytanie sprawdzajace liczbe misji usera gdy edytuje ja administrator
    	$query = $em->createQuery('SELECT u.missionNumber
			 FROM mcscMissionBundle:Users u
			 WHERE u.idUser = '.$userid);
    	$checknumberofmission = $query->getResult();
    	if (!($this->get('security.context')->isGranted('ROLE_ADMIN')) && $user->getIdUser() != $userid)
    	{
    		$error = 'Próba edycji nie swojej misji';
    		$this->createLog($error , $user->getIdPassword());
    		return $this->render('mcscMissionBundle:Default:404.html.twig',array('error' => 'Nie możesz edytować tej misji. Nie 
    			posiadasz uprawnień lub nie jesteś autorem.'));
    	}
    	$form = $this->createForm(new MissionInfo(),$mission)
    	->add('Zapisz', 'submit');
    	$form->handleRequest($request);
    	
    	
    	if ($form->isValid()) {
    		
    		$data = new \DateTime();
    		$datacheck = $data->format('Y-m-d H:i:s');
    		$missiondeadline = $mission->getDeadline()->format('Y-m-d H:i:s');
    		$missiondata = $mission->getStartDate()->format('Y-m-d H:i:s');
    		if(($datatest < $missiondata) && ($checknumberofmission['0']['missionNumber'] >= 4) )
    		{
    			return $this->render('mcscMissionBundle:Default:404.html.twig',array('error' => ('Nie możesz ustawić takiej daty rozegrania: '.$missiondata.' Osiągnięto limit misji.')));
    		}

    		$em->flush();
    		$query = $em->createQuery('SELECT IDENTITY(slot.missionSection) AS slot_section_id,
		 IDENTITY(section.missionSection) AS section_mission_id
		 FROM mcscMissionBundle:missionSlots slot
		 INNER JOIN mcscMissionBundle:missionSection section WITH section.section_id = slot.missionSection
		 INNER JOIN mcscMissionBundle:mission mission WITH mission.mission_id = section.missionSection
		 WHERE mission.mission_id ='.$event_id);
    		$test = count($query->getResult());
    		$mission->setNumberOfSlots($test);
    		
    		$query = $em->createQuery('SELECT IDENTITY(slot.missionSection) AS slot_section_id,
		 IDENTITY(section.missionSection) AS section_mission_id
		 FROM mcscMissionBundle:missionSlots slot
		 INNER JOIN mcscMissionBundle:missionSection section WITH section.section_id = slot.missionSection
		 INNER JOIN mcscMissionBundle:mission mission WITH mission.mission_id = section.missionSection
		 WHERE slot.player is null and mission.mission_id ='.$event_id);
    		$mission->setFreeSlots(count($query->getResult()));
    		$em->persist($mission);
    		$em->flush();
    		
    		$this->addFlash(
    				'success',
    				'Wydarzenie zostało edytowane'
    		);
    		
        }
    	return $this->render('mcscMissionBundle:Default:mission_edit.html.twig', array('form' => $form->createView()));
 }
   

	public function loginAction(Request $request)
	{
		
		$authenticationUtils = $this->get('security.authentication_utils');
		
		$error = $authenticationUtils->getLastAuthenticationError();

		$lastUsername = $authenticationUtils->getLastUsername();
		return $this->render(
				'mcscMissionBundle:Default:login.html.twig',
				array(
						// last username entered by the user
						'last_username' => $lastUsername,
						'error'         => $error,
				)
		);
	}
	public function loginCheckAction()
	{
		
	}
	public function logoutAction(Request $request)
	{
		$this->container->get('security.context')->setToken(NULL);
		return $this->redirect($this->generateUrl('login'));
	}
	
	public function errorAction(Request $request)
	{
		$this->checkNumberOfMission();

		return $this->render('mcscMissionBundle:Default:404.html.twig');
	}
	
	//funkcja zliczająca liczbe misji otworzonych(uwzglednia startdate)
	private function checkNumberOfMission()
	{
	
		$data = new \DateTime();
		$dataformat = $data->format('Y-m-d H:i:s');
		$user = $this->getUser();
		if ($user->getMissionNumber() >= 0)
		{
			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery('SELECT mission.start_date AS mission_start
			 FROM mcscMissionBundle:mission mission
			 INNER JOIN mcscMissionBundle:Users user WITH user.idUser = mission.author_id
			 WHERE mission.start_date > :data AND mission.author_id =
			 '.$user->getIdUser())
				 ->setParameter('data', $dataformat);
			$isjoin = $query->getResult();
			$missionNumber = 0;
			if ($isjoin != NULL)
			{
				foreach ($isjoin as $missionNumbers)
				{
					$missionNumber++;
	
				}
			}
				
		}
		$user->setMissionNumber($missionNumber);
		$em->flush();
	}
	
	private function createLog($message, $userid)
	{
		$log = new Logs();
		$user = $this->getUser();
		$log->setLogdate(new \DateTime());
		$log->setLogmessage($message);
		$log->setUserlog($userid);
		$em = $this->getDoctrine()->getManager();
		$em->persist($log);
		$em->flush();
	}
	

	private function checkSlotNumber()
	{
		$data = new \DateTime();
		$dataformat = $data->format('Y-m-d H:i:s');
		$user = $this->getUser();
		$userid= $user->getIdUser();
		$em = $this->getDoctrine()->getManager();
		if ($user->getSlotsMissionNumber() >= 0)
		{
				
			$query = $em->createQuery('SELECT slot.user_id,mission.start_date,
				IDENTITY(slot.missionSection) AS slot_section_id,
		 IDENTITY(section.missionSection) AS section_mission_id
		 FROM mcscMissionBundle:missionSlots slot
		 INNER JOIN mcscMissionBundle:missionSection section WITH section.section_id = slot.missionSection
		 INNER JOIN mcscMissionBundle:mission mission WITH mission.mission_id = section.missionSection
		 WHERE mission.start_date > :data AND slot.user_id =
		 '.$userid)
			 ->setParameter('data', $dataformat);
			$test = count($query->getResult());
				
				
		}
	
		$user->setSlotsMissionNumber($test);
		$em->flush();
	}
	
	/**
	 * Sprawdza gracz jest już zapisany na daną misję
	 */
	private function checkIsJoinedThisMission($player_id, $mission_id)
	{
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT IDENTITY(slot.missionSection) AS slot_section_id,
		 IDENTITY(section.missionSection) AS section_mission_id
		 FROM mcscMissionBundle:missionSlots slot
		 INNER JOIN mcscMissionBundle:missionSection section WITH section.section_id = slot.missionSection
		 INNER JOIN mcscMissionBundle:mission mission WITH mission.mission_id = section.missionSection
		 WHERE slot.user_id LIKE :player AND mission.mission_id = :mission_id')
				 ->setParameter('player', $player_id)
		         ->setParameter('mission_id', $mission_id);
		if ($query->getResult() != NULL) return true;
		else return false;
		
	}
	
}  





