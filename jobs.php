<?php
require_once("settings.php");
$body_id="Jobs_Page";
include "header.inc";

function esc($v){return htmlspecialchars((string)$v,ENT_QUOTES,"UTF-8");}

$conn=@mysqli_connect($host,$user,$pwd,$dbname);
if(!$conn){die("<h1>Database connection error</h1><p>Please try again later.</p>");}
mysqli_set_charset($conn,"utf8mb4");

/*Added this code to create the jobs table if it doesn't exist already*/
mysqli_query($conn,"CREATE TABLE IF NOT EXISTS jobs(
job_ref VARCHAR(5) NOT NULL,
title VARCHAR(100) NOT NULL,
description TEXT NOT NULL,
PRIMARY KEY(job_ref)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

/*Takes ther jobs and puts it in a array*/
$jobs=[];
$res=mysqli_query($conn,"SELECT job_ref,title,description FROM jobs");
if($res){
while($row=mysqli_fetch_assoc($res)){
$jobs[$row["job_ref"]]=$row;
}
mysqli_free_result($res);
}
mysqli_close($conn);

/*If the database for some reason is empty, it will display this (Added this to mainly help me)*/
if(!isset($jobs["EHK01"])){$jobs["EHK01"]=["title"=>"Ethical Hacker","description"=>"Job details are currently unavailable."]; }
if(!isset($jobs["SEC02"])){$jobs["SEC02"]=["title"=>"Security Analyst","description"=>"Job details are currently unavailable."]; }
if(!isset($jobs["THH03"])){$jobs["THH03"]=["title"=>"Threat Hunter","description"=>"Job details are currently unavailable."]; }
?>

            <aside>
                <h2>About One Studio</h2>
                <p><strong>One Studio</strong> is a revolutionary cybersecurity services company dedicated to safeguarding organizations 
                           from rapidly evolving threats on the internet.<strong>Threat hunting, security analysis, ethical hacking,
                           and cyber risk assessment</strong> are among our team's areas of expertise.
                </p>
                <p>Our primary goals include creating safe systems, strengthening digital infrastructures, and
                   teaching future cybersecurity specialists to adhere to global industry standards.  
                   At One Studio, we believe in innovation, reliability, and proactive security.
                </p>
            </aside>

        <main>
            <section class="job-card">
                <div class="job-image">
                    <img src="images/ethical-hacker.jpg" alt="Ethical Hacker">
                </div>
                <div class="job-text">
                     <h2>Position Title: <?php echo esc($jobs["EHK01"]["title"]); ?></h2>
                        <p><?php echo esc($jobs["EHK01"]["description"]); ?></p>
                    <h3>Key Responsibilities</h3>
                    <ul>
                        <li>Perform systematic evaluations on both networks and applications using both automated and manual 
                            testing</li>
                        <li>Anaylze methods to expose system vulnerabilities and rank them according to the potential risks, they
                            might pose to the organisation</li>
                        <li>Execute thorough security audits to measure the effectiveness of existing security controls and 
                            policies</li>
                        <li>Help organizations assess potential threats, prioritize security measures, and reduce overall cyber
                            risk exposure</li>
                    </ul>
                    <h3>Academic Qualifications</h3>
                    <h4>Essential</h4>
                    <ul>
                        <li>Bachelor's degree in <strong>Cybersecurity, Computer Science, Information Technology or Information 
                            Security</strong></li>
                        <li>Training or coursework in <strong>Network Security, Operating Systems, and Ethical Hacking
                        </strong></li>
                        <li>Completion of at least one globally recognized cybersecurity certification course (e.g.,<strong> CEH 
                            training</strong>)</li>
                    </ul>
                    <h4>Preferable</h4>
                    <ul>
                        <li>Master's degree in <strong>Cybersecurity or Information Security</strong></li>
                        <li>Specialized certifications such as <strong>OSCP, CompTIA Security+, eJPT, or CPTS</strong></li>
                        <li>Academic projects or lab experience in <strong>penetration testing or malware analysis</strong></li>
                        <li>Advanced Bootcamp Certifications enhancing your skills</li>
                    </ul>
                    <h3>Essential Skills</h3>
                    <ul>
                        <li>Strong understanding of <strong>TCP/IP, VPNs, and routing</strong></li>
                        <li>Ability to detect and exploit vulnerabilities</li>
                        <li>Able to utilize penetration testing tools (<strong>Nmap, Burp Suite, Metasploit</strong>)</li>
                        <li>Basic knowledge of programming languages and script (<strong>Python, Bash, PowerShell</strong>)</li>
                        <li>Strong analytical and problem-solving skills</li>
                </ul>
                <h3>Experience Requirements</h3>
                <ul>
                    <li>1-3 years of experience in penetration testing or vulnerability analysis</li>
                    <li>Hands-on understanding with security tools such as <strong>Nmap, Burp Suite, or Metasploit</strong></li>
                    <li>Practical knowledge in testing web applications, networks, or APIs</li>
                </ul>
                </div>
            </section>

            <aside>
                <h3>Additional Information</h3>
                <ol>
                    <li>Average Salary: QAR 17,000-23,500/month</li> 
                    <li>Reference Number: EHK01</li>
                    <li>Reports To: Senior Cybersecurity Manager</li>
                </ol>
            </aside>

            <section class="job-card">
    <div class="job-image">
        <img src="images/security-analyst.jpg" alt="Job 2">
    </div>
    <div class="job-text">
                <h2>Position Title: <?php echo esc($jobs["SEC02"]["title"]); ?></h2>
                <p><?php echo esc($jobs["SEC02"]["description"]); ?></p>
                <h3>Key Responsibilities</h3>
                <ul>
                    <li>Continuously monitor networks, systems, and data for suspicious activity and potential security breaches using 
                        tools like SIEM.</li>
                    <li>Act as a first responder to security incidents, investigating the source of the breach,and implementing 
                        solutions to prevent it happening again.</li>
                    <li>Ensure the organization abides by security policies, and generate reports on security status,incidents, and 
                        recommendations for management.</li> 
                </ul>
                <h3>Academic Qualifications</h3>
                <h4>Essential</h4>
                <ul>
                    <li>Bachelor's degree in <strong> Cybersecurity, Computer Science, Information Technology, or 
                        Network Technology</strong></li>
                    <li>Coursework in <strong>Network Security, Operating Systems, and Information Security Principles</strong></li>
                    <li>Basic understanding of security frameworks (e.g., <strong>ISO 27001, NIST</strong>)</li>
                </ul>
                <h4>Preferable</h4>
                <ul>
                    <li>Master's degree in <strong>Cybersecurity, Information Security</strong></li>
                    <li>Professional certifications such as <strong>CompTIA Security+</strong>                                                                                                                                                                                                                                                                                                                                                                                  (Associate level)</li>
                    <li>Lab experience in <strong>threat analysis, or security monitoring</strong></li>
                </ul>
                <h3>Essential Skills</h3>
                <ul>
                    <li>Strong understanding of <strong>network protocols, firewalls, and intrusion detection systems</strong></li>
                    <li>Ability to scrutinize security alerts and investigate incidents</li>
                    <li>Proficiency with SIEM tools (e.g., <strong>Splunk, QRadar</strong>)</li>
                    <li>Basic familiarity with operating systems (<strong>Windows, Linux</strong>)</li>
                    <li>Strong analytical, problem-solving, and communication skills</li>
                </ul>
                <h3>Experience Requirements</h3>
                <ul>
                    <li>1-4 years in security monitoring, incident response, or IT support</li>
                    <li>Hands-on experience with SIEM dashboards, alert triage, and reporting</li>
                    <li>Experience in investigating security incidents and documenting findings</li>
                    </ul>
                    </div>
            </section>

            <aside>
                <h3>More Details</h3>
                <ol>
                    <li>Average Salary: QAR 10,350-18,750/month</li> 
                    <li>Reference Number: SEC02</li>
                    <li>Reports To: Senior Security Analyst</li>
                </ol>
            </aside>

            <section class="job-card">
    <div class="job-image">
        <img src="images/threat-hunter.jpg" alt="Threat Hunter">
    </div>
    <div class="job-text">
                <h2>Position Title: <?php echo esc($jobs["THH03"]["title"]); ?></h2>
                <p><?php echo esc($jobs["THH03"]["description"]); ?></p>
                <h3>Key Responsibilities</h3>
                <ul>
                    <li>Constantly hunt for hidden, or unknown threats that automated solutions may miss, such as those from 
                        insiders or sophisticated adversaries.</li>
                    <li>Scour large volumes of data from network logs, system logs, and threat intelligence feeds to find 
                        suspicious patterns.</li>
                    <li>Create and test theories about potential compromises based on threat intelligence, behavioral anomalies, and 
                        attack path analysis.</li>
                </ul>
                <h3>Academic Qualifications</h3>
                <h4>Essential</h4>
                <ul>
                    <li>Bachelor's degree in <strong>Cybersecurity, Computer Science, or Information Technology</strong></li>
                    <li>Coursework or training in <strong>Network Security, Incident Response, and Threat Analysis</strong></li>
                </ul>
                <h4>Preferable</h4>
                <ul>
                    <li>Master's degree in <strong>Cybersecurity or Information Security</strong></li>
                    <li>Professional certifications such as <strong>OSCP, CEH, GCIH, or CPT</strong></li>
                    <li>Academic projects or lab experience in <strong>threat hunting, malware analysis, or security monitoring
                    </strong></li>
                </ul>
                <h3>Essential Skills</h3>
                <ul>
                    <li>Strong knowledge of <strong>network protocols, firewalls, and intrusion detection systems</strong></li>
                    <li>Proficiency with threat intelligence platforms</li>
                    <li>Ability to analyze logs and detect anomalies</li>
                    <li>Strong analytical and problem-solving skills</li>
                </ul>
                <h3>Experience Requirements</h3>
                <ul>
                    <li>2-3 years in security monitoring, threat analysis, or incident response</li>
                    <li>Hands-on experience using SIEM tools to investigate threats</li>
                    <li>Experience detecting and documenting unusual network or system behavior</li>
                </ul>
                </div>
            </section>

            <aside>
                <h3>Supplementary Facts</h3>
                <ol>
                    <li>Average Salary: QAR 16,000-21,250/month</li> 
                    <li>Reference Number: THH03</li>
                    <li>Reports To: Head of Security Operations</li>
                </ol>
            </aside>
            <section>
            <p>If you qualify for any of the above posts</p>

            <div class="cta-cards">
                <div class="cta-card">
                <a href="apply.php">Apply Now</a>
                </div>
            </div>
            </section>
        </main>
<?php include 'footer.inc'; ?>