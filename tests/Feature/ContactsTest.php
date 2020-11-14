<?php

namespace Tests\Feature;

use App\Contact;
use Carbon\Carbon;
use Facade\FlareClient\Api;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ContactTest extends TestCase
{


    use RefreshDatabase;
   /** @test */
    public function a_contact_can_be_added(){

        $this->withoutExceptionHandling();

$this->post('/api/contacts',$this-> data());



        $contact = Contact::first();

       
        $this->assertEquals('test name',$contact->name);
        $this->assertEquals('test@email.com',$contact->email) ;
        $this->assertEquals('05/14/1988',$contact->birthday) ;
        $this->assertEquals('ABC string',$contact->company) ;

    }

    public function field_are_required()
    {

        collect(['name','email','birthday','company'])->each(function($field){
        
        
    $response = $this->post('/api/contacts', array_merge($this->data(), [$field=>'']));

    $response->assertSessionHasErrors($field);
$this->assertCount(0,Contact::all());
        
            
        });
    
        }



        public function email_must_be_a_valid_email(){

           
        
        
                $response = $this->post('/api/contacts', array_merge($this->data(), ['email'=>'NOT AN EMAIL']));
            
                $response->assertSessionHasErrors('email');
            $this->assertCount(0,Contact::all());
                      
        }
        
        public function birthday_are_properly_store(){

           $this->withoutExceptionHandling();
        
        
            $response = $this->post('/api/contacts', array_merge($this->data() ));
        
            $response->assertSessionHasErrors('email');
        $this->assertCount(1,Contact::all());
        $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
        $this->assertEquals('05-14-1988' , Contact::first()->birthday->format('m-d-Y'));
                  
    }
    

    public function a_contact_can_be_retrieved(){


        $contact = factory(Contact::class)->create();

      $response = $this->get('Api/Contacts/'. $contact->id);


      $response->assertJson([

'name' => $contact->name,
'email' => $contact->email,
'birthday' => $contact->birthday,
'company' => $contact->name,

]);



    }


    public function a_contact_can_be_patched(){


        $this->withoutExceptionHandling();


        $contact = factory(Contact::class)->create();

        $response = $this->patch('api/contacts/' .$contact->id, $this->data());

        $contact = $contact->fresh();


        $this->assertEquals('test name',$contact->name);
        $this->assertEquals('test@email.com',$contact->email) ;
        $this->assertEquals('05/14/1988',$contact->birthday->format('m/d/Y')) ;
        $this->assertEquals('ABC string',$contact->company) ;
    }

    

private function data(){
    return [
        'name'=> 'test name',
        'email'=> 'test@email.com',
        'birthday' => '05/14/1988',
        'company' => 'ABC string',
    ];
}
 

}