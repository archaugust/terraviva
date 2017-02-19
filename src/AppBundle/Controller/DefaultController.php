<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine();

    	$content = $em->getRepository('AppBundle:HomePage')->find(1);
    	$featured = $em->getRepository('AppBundle:ProductItem')->findBy(array('featured' => 1, 'publish' => 1),[],10);

        return $this->render('default/index.html.twig', array(
        	'content' => $content,
        	'specials' => $featured,
        ));
    }

    /**
     * @Route("/member", name="member")
     */
    public function memberAction() {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            return $this->redirectToRoute('admin');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $this->addFlash(
                'info',
                'You have successfully logged in.'
            );
        }
        else {
            $this->addFlash(
                'info',
                'Please login to your account.'
            );
        }
        return $this->redirectToRoute('homepage');
    }

    
    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->render('admin/index.html.twig', array(
                'header' => 'Dashboard',
            )
        );
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias'=>'contact'));
    
    	$contact = new Contact();
    	$form = $this->createForm(ContactType::class, $contact);
    
    	$form->handleRequest($request);
    	if ($form->isValid()):
    	$data = $request->request->all();
    	if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])):
    	//your site secret key
    	$secret = '6LcrFQ0UAAAAAPL3VHWomOKF-BcTB1cd-NrYlVGj';
    	//get verify response data
    	$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
    	 
    	// if the captcha is cleared with google, send the mail and echo ok.
    	if ($response['success'] != false) :
    	$contact
    	->setDateSubmitted(time())
    	;
    	$em->persist($contact);
    	$em->flush();
    
    	$content = 'Thank you. Your message has been sent.';
    
    	$message = \Swift_Message::newInstance()
    	->setSubject('Contact')
    	->setFrom(array('noreply@brightsmiles.nz' => 'Bright Smiles Dental Care'))
    	//					->setTo('info@brightsmiles.nz')
    	->setTo('archaugust@yahoo.com')
    	->setBody(
    			$this->renderView('email/contact.html.twig', array(
    					'data' => $contact
    			)),
    			'text/html'
    			);
    
    	$this->get('mailer')->send($message);
    	else:
    	$content = 'Robot verification failed, please try again.';
    	endif;
    	else:
    	$content = 'Please click on the reCAPTCHA box.';
    	endif;
    	return $this->render('default/contact-send.html.twig', array(
    			'content' => $content,
    			'form' => $form->createView()
    	));
    	else :
    	$content->setHits($content->getHits() + 1);
    	$em->flush();
    	endif;
    
    	return $this->render('default/contact.html.twig', array(
    			'content' => $content,
    			'form' => $form->createView()
    	));
    }
    
    /**
     * @Route("/contact-send")
     */
    public function mailAction(Request $request)
    {
        $data = $request->request->all();

        if ($data['name'] && $data['email']):
            if(isset($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])):
                //your site secret key
                $secret = '6LcrFQ0UAAAAAPL3VHWomOKF-BcTB1cd-NrYlVGj';
                //get verify response data
                $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

                // if the captcha is cleared with google, send the mail and echo ok.
                if ($response['success'] != false) :
                    //contact form submission code

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Contact')
                        ->setFrom(array('info@kingswaydist.co.nz' => 'Kingsway Distributor'))
                        ->setTo('info@kingswaydist.co.nz')
                        ->setBody(
                            $this->renderView(
                                'email/contact.html.twig',
                                $data
                            ),
                            'text/html'
                        )
                    ;
                    $this->get('mailer')->send($message);

                    $content = 'Thank you. Your message has been magically sent.';
                else:
                    $content = 'Robot verification failed, please try again.';
                endif;
            else:
                $content = 'Please click on the reCAPTCHA box.';
            endif;
        else :
            $content = 'Please enter all required info.';
        endif;

        return $this->render('/default/contact-send.html.twig', array(
                'title' => 'Contact',
                'content' => $content,
                'contact' => array('name' => $data['name'],
                    'email' => $data['email'],
                    'message' => $data['message'],
                    'subject' => $data['subject'],
                )
            )
        );
    }

    public function hideEmailAction($email)
    {
        $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
        $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
        for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
        $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
        $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
        $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
        $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
        $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

        return new Response('<span id="'.$id.'">[javascript protected email address]</span>'.$script);
    }
}