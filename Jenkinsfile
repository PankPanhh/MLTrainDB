pipeline {
    agent { label 'ML-Agent-02' } // Jenkins agent có SSH key đã add GitHub

    environment {
        APP_PATH = "${WORKSPACE}"                 // Thư mục project Laravel
        LOG_FILE = "${WORKSPACE}/laravel.log"    // Log file
    }

    options {
        timeout(time: 30, unit: 'MINUTES')      // Giới hạn pipeline 30 phút
        timestamps()                             // Log có timestamp
        ansiColor('xterm')                       // Màu sắc log
    }

    stages {
        stage('Checkout') {
            steps {
                echo "🔄 Pull branch hiện tại từ GitHub via SSH"
                checkout([$class: 'GitSCM',
                    branches: [[name: '*/main']], // hoặc tên branch chính
                    doGenerateSubmoduleConfigurations: false,
                    extensions: [],
                    userRemoteConfigs: [[
                        url: 'git@github.com:PankPanhh/MLTrainDB.git',
                        credentialsId: '', // Nếu dùng SSH key agent mặc định để trống
                    ]]
                ])
            }
        }

        stage('Composer Install') {
            steps {
                echo "📦 Running composer install..."
                dir("${APP_PATH}") {
                    sh 'composer install --no-interaction --prefer-dist'
                }
            }
        }

        stage('Clear Laravel Cache') {
            steps {
                echo "🧹 Clearing Laravel cache..."
                dir("${APP_PATH}") {
                    sh '''
                    php artisan config:clear
                    php artisan cache:clear
                    php artisan route:clear
                    php artisan view:clear
                    '''
                }
            }
        }

        stage('Serve Laravel') {
            steps {
                echo "🚀 Serving Laravel app..."
                dir("${APP_PATH}") {
                    // Chạy serve Laravel trong background, log ra file
                    sh """
                    nohup php artisan serve --host=127.0.0.1 --port=9000 > ${LOG_FILE} 2>&1 &
                    """
                }
            }
        }
    }

    post {
        success {
            echo "✅ Pipeline chạy thành công! Log xem tại ${LOG_FILE}"
        }
        failure {
            echo "❌ Pipeline thất bại! Kiểm tra log tại ${LOG_FILE}"
        }
    }
}
