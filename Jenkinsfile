pipeline {
  agent any
  environment {
    IMAGE_NAME = 'registry.example.com/audit-management-system:${BUILD_NUMBER}'
  }
  stages {
    stage('Checkout source from Git') {
      steps { checkout scm }
    }
    stage('Install dependencies') {
      steps { sh 'composer install --no-interaction --prefer-dist' }
    }
    stage('Run PHPUnit tests') {
      steps { sh 'php artisan test' }
    }
    stage('Build Docker image') {
      steps { sh 'docker build -t $IMAGE_NAME .' }
    }
    stage('Push Docker image to container registry') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'registry-creds', usernameVariable: 'REG_USER', passwordVariable: 'REG_PASS')]) {
          sh 'echo $REG_PASS | docker login registry.example.com -u $REG_USER --password-stdin'
          sh 'docker push $IMAGE_NAME'
        }
      }
    }
    stage('Deploy to OpenShift') {
      steps {
        withCredentials([string(credentialsId: 'oc-token', variable: 'OC_TOKEN')]) {
          sh 'oc login --token=$OC_TOKEN --server=https://api.openshift.example.com:6443'
          sh 'oc apply -f k8s/'
          sh 'oc set image deployment/audit-management audit-management=$IMAGE_NAME'
        }
      }
    }
  }
}
