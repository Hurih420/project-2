
<?php
$body_id="about-page";
include "header.inc";
?>

<main>
    <section id="intro">
        <h1>About Us</h1>

        <p>
            We are a group of young, passionate students studying computer science named 
            <strong>"Against all Odds"</strong> who are enthusiastic about web development 
            and cyber security.
        </p>

        <p>
            To create outstanding and user-friendly web solutions, our 
            team combines skills in <strong>HTML, CSS, and project management.</strong>
        </p>

        <p>
            We cherish collaborating on creative projects while discovering new technology 
            together.
        </p>
    </section>

    <section id="group-photos" class="images">
       <figure>
            <img src="images/grouphpoto.jpg" alt="Group photo of all team members" 
            width="300">
            <figcaption>Our Group Photo</figcaption>
        </figure>
        <figure>
            <img src="images/mohammad.jpg" alt="Mohammad Jassim smiling" width="300">
            <figcaption>Mr.Mohammad</figcaption>
        </figure>
    </section>

    <aside class="skills">
        <h2>Our Key Skills</h2>
        <ul>
            <li>HTML and CSS</li>
            <li>Responsive Web Design</li>
            <li>Project Management</li>
            <li>Team Collaboration</li>
        </ul>
    </aside>

    <aside class="tools">
        <h2>Our Favorite Tools</h2>
        <ul>
            <li><cite>Visual Studio Code</cite></li>
            <li><cite>Virtual Box</cite></li>
            <li><cite>Nmap</cite></li>
            <li><cite>ChatGPT</cite></li>
        </ul>
    </aside>

    <section id="students">
        <h2>Student Names and Student IDs</h2>
        <ol>
            <li>Abu Huraira
                <ul>
                    <li>106223971</li>
                </ul>
            </li>
            <li>Omar Alazzawe
                <ul>
                    <li>106212209</li>
                </ul>
            </li>
            <li>Mohammad Jassim M S Al Suwaidi
                <ul>
                    <li>106204659</li>
                </ul>
            </li>
        </ol>
    </section>

    <section id="contributions">
        <h2>Members' Contributions</h2>
        <dl class="member-contributions">
            <dt>Abu Huraira</dt>
            <dd>
                Worked on:
                <ul>
                    <li> <strong>index.html</strong></li>
                    <li><strong>about.html</strong> with Omar</li> 
                    <li><strong>jobs.html</strong> alone </li>
                    <li><strong>css</strong></li>
                </ul>
            </dd>

            <dt>Omar Alazzawe</dt>
            <dd>
                Worked on:
                <ul> 
                    <li><strong>index.html</strong></li>
                    <li><strong>about.html</strong> with Abu Huraira</li>
                    <li><strong>apply.html</strong> alone</li>
                    <li> Also worked on <strong>css</strong></li>
                </ul>
            </dd>

            <dt>Mohammad Jassim M S Al Suwaidi</dt>
            <dd>
            Had <em>legal issues</em>, so could not assist with HTML knowledge.
            </dd>
        </dl>
    </section>

    <section id="programming-skills">
        <h2>Programming Skills</h2>
        <dl class="member-skills">
            <dt>Abu Huraira</dt>
            <dd>
                <ul>
                    <li>Python</li>
                    <li><abbr title="HyperText Markup Language">HTML</abbr></li>
                    <li><abbr title="Cascading Style Sheets">CSS</abbr></li>
                    <li><abbr title="Structured Query Language">MySQL</abbr></li>
                    <li><abbr title="Hypertext Preprocessor">PHP</abbr></li>
                    <li> Ruby</li>   
                </ul>    
            </dd>

            <dt>Omar Alazzawe</dt>
            <dd>
                <ul>
                    <li>Python</li>
                    <li><abbr title="HyperText Markup Language">HTML</abbr></li>
                    <li><abbr title="Cascading Style Sheets">CSS</abbr></li>
                   <li><abbr title="Structured Query Language">MySQL</abbr></li>
                    <li><abbr title="Hypertext Preprocessor">PHP</abbr></li>
                    <li> Ruby</li>
                </ul>
            </dd>

            <dt>Mohammad Jassim M S Al Suwaidi</dt>
            <dd>
                <ul>
                    <li><abbr title="HyperText Markup Language">HTML</abbr></li>
                    <li><abbr title="Cascading Style Sheets">CSS</abbr></li>
                    <li><abbr title="Structured Query Language">MySQL</abbr></li>
                    <li> Ruby</li>
                </ul>
            </dd>
        </dl>
    </section>

    <section id="member-info">
        <h2>Members' Information</h2>
        <table>
            <thead>
                <tr>
                    <th scope="col">Student Name</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Nationality</th>
                    <th >Hobbies</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Abu Huraira</td>
                    <td>106223971</td>
                   <td><abbr title="Pakistan">PA</abbr></td>
                    <td>Gaming, Watching football</td>
                </tr>
                <tr>
                    <td>Omar Alazzawe</td>
                    <td>106212209</td>
                    <td><abbr title="United States of America">USA</abbr></td>
                    <td>Reading, 3D Modelling</td>
                </tr>
                <tr>
                    <td>Mohammad Jassim</td>
                    <td>106204659</td>
                    <td><abbr title="Qatar">QA</abbr></td>
                    <td>Driving, Playing football</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php include 'footer.inc'; ?>
