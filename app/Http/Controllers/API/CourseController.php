<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $data = array(
            array(
                "id" => 1,
                "name" => "Kotlin for Beginners",
                "description" => "Learn the basics of Kotlin, a modern programming language that makes developers happier. This course covers all the fundamental concepts and provides hands-on exercises to help you start coding in Kotlin quickly.",
                "lecturer" => array(
                    "name" => "John Doe",
                    "bio" => "John Doe is a software engineer with over 10 years of experience in mobile and web development. He has been working with Kotlin since its inception and is passionate about teaching new developers."
                )
            ),
            array(
                "id" => 2,
                "name" => "Advanced Kotlin",
                "description" => "Deep dive into Kotlin features such as coroutines, advanced collections, and DSLs. This course is designed for developers who already have a basic understanding of Kotlin and want to take their skills to the next level.",
                "lecturer" => array(
                    "name" => "Jane Smith",
                    "bio" => "Jane Smith is a senior software developer with a focus on Kotlin and Java. She has contributed to several open-source projects and enjoys sharing her knowledge through teaching and writing."
                )
            ),
            array(
                "id" => 3,
                "name" => "Kotlin for Android Development",
                "description" => "This course teaches you how to use Kotlin for Android development. Learn how to build robust and efficient Android apps using Kotlin, including how to leverage Kotlin's features to reduce boilerplate code and increase productivity.",
                "lecturer" => array(
                    "name" => "Alice Johnson",
                    "bio" => "Alice Johnson is an Android developer with 8 years of experience in the industry. She has been using Kotlin for Android development since Google announced official support in 2017."
                )
            ),
            array(
                "id" => 4,
                "name" => "Kotlin Coroutines and Flow",
                "description" => "Master asynchronous programming in Kotlin with Coroutines and Flow. This course covers everything from the basics of coroutines to advanced concepts like structured concurrency and reactive streams.",
                "lecturer" => array(
                    "name" => "Robert Brown",
                    "bio" => "Robert Brown is a backend developer and Kotlin enthusiast. He has extensive experience with asynchronous programming and has been using Kotlin's coroutines since they were introduced."
                )
            ),
            array(
                "id" => 5,
                "name" => "Kotlin for Server-Side Development",
                "description" => "Learn how to use Kotlin for server-side development with frameworks like Ktor and Spring Boot. This course will teach you how to build scalable and maintainable server-side applications using Kotlin.",
                "lecturer" => array(
                    "name" => "Michael Williams",
                    "bio" => "Michael Williams is a full-stack developer with a passion for server-side technologies. He has been developing server-side applications with Kotlin for several years and enjoys teaching others how to leverage Kotlin's strengths on the server."
                )
            ),
            array(
                "id" => 6,
                "name" => "Functional Programming in Kotlin",
                "description" => "Explore the functional programming paradigm in Kotlin. This course covers functional programming concepts such as immutability, higher-order functions, and lambda expressions, and how to apply them effectively in Kotlin.",
                "lecturer" => array(
                    "name" => "Emily Davis",
                    "bio" => "Emily Davis is a software architect with a deep understanding of functional programming. She has been using functional programming languages for over a decade and has successfully applied functional concepts in Kotlin."
                )
            ),
            array(
                "id" => 7,
                "name" => "Kotlin DSLs (Domain-Specific Languages)",
                "description" => "Learn how to create Domain-Specific Languages (DSLs) in Kotlin to make your code more expressive and easier to read. This course covers the principles of DSL design and practical examples of building DSLs in Kotlin.",
                "lecturer" => array(
                    "name" => "David Martinez",
                    "bio" => "David Martinez is a software developer with a special interest in language design and DSLs. He has created several DSLs in Kotlin and enjoys exploring new ways to improve code expressiveness."
                )
            ),
            array(
                "id" => 8,
                "name" => "Kotlin Testing Strategies",
                "description" => "This course covers testing strategies in Kotlin, including unit testing, integration testing, and using popular testing libraries such as JUnit and MockK. Learn how to write effective tests to ensure your Kotlin code is reliable and maintainable.",
                "lecturer" => array(
                    "name" => "Laura Wilson",
                    "bio" => "Laura Wilson is a test automation engineer with a strong background in quality assurance. She has been working with Kotlin for testing applications and is an advocate for best testing practices."
                )
            ),
            array(
                "id" => 9,
                "name" => "Building REST APIs with Kotlin",
                "description" => "Learn how to build RESTful APIs using Kotlin and frameworks like Ktor and Spring Boot. This course will guide you through the process of creating robust and efficient APIs with Kotlin.",
                "lecturer" => array(
                    "name" => "James Taylor",
                    "bio" => "James Taylor is a backend developer specializing in API development. He has been building RESTful APIs with Kotlin and is passionate about teaching others how to create scalable API services."
                )
            ),
            array(
                "id" => 10,
                "name" => "Kotlin Multiplatform Projects",
                "description" => "Explore Kotlin Multiplatform and learn how to share code across different platforms such as Android, iOS, and the web. This course covers the fundamentals of Kotlin Multiplatform and provides hands-on examples of building cross-platform applications.",
                "lecturer" => array(
                    "name" => "Sarah Thompson",
                    "bio" => "Sarah Thompson is a mobile developer with expertise in cross-platform development. She has been using Kotlin Multiplatform to build applications that run on multiple platforms and enjoys sharing her knowledge through teaching."
                )
            )
        );
        

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
