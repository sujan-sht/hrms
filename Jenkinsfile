pipeline {
    agent any
    
    stages {
        stage('Setup Webhook') {
            steps {
                script {
                    def webhookToken
                    withCredentials([string(credentialsId: 'webook-secret-bidhee-admin', variable: 'WEBHOOK_TOKEN')]) {
                        webhookToken = WEBHOOK_TOKEN
                    }
                    properties([
                        pipelineTriggers([
                            GenericTrigger(
                                genericVariables: [
                                    [key: 'ref', value: '$.ref', expressionType: 'JSONPath']
                                ],
                                token: webhookToken,
                                printContributedVariables: true,
                                printPostContent: true
                            )
                        ])
                    ])
                }
            }
        }
        stage('Deploy') {
            steps {
                sshagent(credentials: ['divash-macbook-air']) {
                    sh '''
                    ssh -o StrictHostKeyChecking=no  -p 2222 bidhee@202.51.68.219 "
                        cd /var/www/html/dev/bottlersnepal-hrms-dev
                        git pull origin bottlers_nepal
                        php artisan module:migrate
                        php artisan op:cl
                    "
                    '''
                    echo "Deploy done !!!."
                }
            }
        }
    }
}
