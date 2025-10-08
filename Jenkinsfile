pipeline {
    agent any
    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/PankPanhh/MLTrainDB.git'
            }
        }
        stage('Composer Install') {
            steps {
                sh 'composer install'
            }
        }
        stage('Clear Laravel Cache') {
            steps {
                sh 'php artisan cache:clear'
            }
        }
        stage('Serve Laravel') {
            steps {
                sh 'php artisan serve --host=127.0.0.1 --port=8000 &'
            }
        }
    }
    post {
        always {
            echo 'Pipeline finished. Check laravel.log for details.'
        }
    }
}
