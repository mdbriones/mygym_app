<?php

namespace Tests\Feature;

use App\Models\ClassType;
use App\Models\ScheduledClass;
use App\Models\User;
use Database\Seeders\ClassTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InstructorTest extends TestCase
{
    // reset the database when running the test
    use DatabaseTransactions;
    

    public function test_instructor_is_redirected_to_instructor_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirectToRoute('instructor.dashboard');

        $this->followRedirects($response)->assertSeeText("Hey Instructor");

    }

    public function test_instructor_can_schedule_a_class()
    {
        // given
        $user = User::factory()->create([
            'role' => 'instructor',
        ]);

        // seed first to be able to get the value in the post request if needed to pass the test
        // and so to get the actual value in the seed/database record
        $this->seed(ClassTypeSeeder::class);

        //when
        // check if able to post/save a record in the database
        $response = $this->actingAs($user)->post('instructor/schedule', [
            'class_type_id' => ClassType::first()->id, // when hardcoding the value is not passable, make a seed first then use that for this value
            'date' => '2023-09-28',
            'time' => '09:30:00'
        ]);

        //then

        // check if the database has the record saved above
        $this->assertDatabaseHas('scheduled_classes', [
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2023-09-28 09:30:00',
        ]);

        // check if the page is redirected to the schedule index route
        $response->assertRedirectToRoute('schedule.index');
    }

    public function test_instructor_can_cancel_class()
    {
        // Given
        $user = User::factory()->create([
            'role' => 'instructor'
        ]);

        $this->seed(ClassTypeSeeder::class);

        $scheduledClass = ScheduledClass::create([
            'instructor_id' => $user->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => '2023-09-21 10:00:00'
        ]);

        // When
        $response = $this->actingAs($user)->delete('/instructor/schedule/'.$scheduledClass->id);

        // dd($scheduledClass);
        
        // Then
        $this->assertDatabaseMissing('scheduled_classes',[
            'id' => $scheduledClass->id
        ]);

    }

    public function test_cannot_cancel_class_less_than_two_hours_before() {
        $user = User::factory()->create([
            'role' => 'instructor'
        ]);

        // dd(now()->setTimezone('Asia/Manila')->addHours(2));

        $this->seed(ClassTypeSeeder::class);
        $scheduledClass = ScheduledClass::create([
            'instructor_id' => $user->id,
            'class_type_id' => ClassType::first()->id,
            'date_time' => now()->setTimezone('Asia/Manila')->addHours(1)->minutes(0)->seconds(0)
        ]);
        
        $response = $this->actingAs($user)
            ->get('instructor/schedule');

        $response->assertDontSeeText('Cancel');

        $response = $this->actingAs($user)
            ->delete('/instructor/schedule/'.$scheduledClass->id);

        $this->assertDatabaseHas('scheduled_classes',[
            'id' =>$scheduledClass->id
        ]);
    }

}
