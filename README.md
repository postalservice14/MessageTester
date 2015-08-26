# MessageTester
AMQP Message Tester

## Usage (example)
1. `curl http://postalservice14.github.io/MessageTester/downloads/message-tester-1.0.0.phar > message-tester.phar`
2. `chmod a+x message-tester.phar`
3. Test the CLI with: `./message-tester.phar --version`
4. Execute: `./message-tester.phar send -H messaging-sign-frontoffice.adpedge.com -p 5673 -u {{AMQP_USER}} -P “{{AMQP_PASSWORD}}" -e MessageTester -m "This is a test" -s`
5. You should see the message sent to the exchange specified.
