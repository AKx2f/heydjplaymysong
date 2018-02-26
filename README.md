# This app project has some vulnerabilities.
# heydjplaymysong
MIT License

Copyright (c) [2011] [Ali Kaba]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

# Requirements
* LAMP stack
* PHP 5.4
* I'm not sure which Linux, Apache or MySQL version I was using but it was around second half of 2013.

# Abstract
Hey DJ Play My Song (HDJPMS) is a music voting system web application. The intended audience of this product is for working professional Disc Jockeys (DJs) however it can be used by friends and family or even someone who plans to hire a DJ. It is a completely separate system from the actual device that is used to use to play the song.
Join the fun, place your playlist online for your friends and family to vote on! Where ever they are!


# Summary of Problems, Opportunity and Directives
HDJPMS’s goal is to do the same outcome task as other music voting system on the market and it is as followed:
* Ability to create a playlist with favorite songs.
* Ability to search a song and request it to be played.
* Ability to vote up or down on an already requested song.

However some of the current systems require the following before voting:
* A setup fee.
* Personal information such as name, address.
* Ownership of the songs which can be a legal issue.
* Direct connection to the music system being used to play the song which is a potential threat.

The risk assessment here is that with guest connected to the music systems with users which can be a potential cause the following depending on the value of the playlist to the DJ/host:
* Negligible.
* Slight loss of competitive advantage.
* Significant loss of competitive advantage.
* Significant financial loss.
* Significant business profit.
* Serious loss of life.

HDJPMS tackles the following ideal:
* Free to use.
* Complete anonymous voting system.
* Fast registration with email for verification only, no name or address needed.
* HDJPMS is an entity of its own, doesn’t connect directly to music system.
* This potential works well with legacy systems or closed systems.
* Peace of mind from malicious software.

With the user creating their own playlist, whether they are a DJ or hiring one for an event, this application can be used as a music database which can later on be used during the event for guest to vote on.
 7
Potential directives would be to partner with a paid music record pool and be offered as an incentive for being part of that record pool.
